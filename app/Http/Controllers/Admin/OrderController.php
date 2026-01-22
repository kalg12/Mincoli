<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\InventoryMovement;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'payments.method'])->latest();

        // Search by Order Number or Customer Name/Email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // Filter by Date Range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Pagination Limit
        $perPage = $request->get('per_page', 10);
        if (!is_numeric($perPage) || $perPage <= 0) $perPage = 10;

        $orders = $query->paginate($perPage)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
         $order = Order::with(['customer', 'items.product.images', 'payments.method', 'statusHistory'])->findOrFail($id);
         return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Use transaction and lockForUpdate to prevent race conditions
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id) {
            $order = Order::with('items')->lockForUpdate()->findOrFail($id);
            
            $request->validate(['status' => 'required|in:pending,paid,shipped,delivered,cancelled,refunded']);
            
            $oldStatus = $order->status;
            
            // Idempotency Check: No change needed
            if ($request->status === $oldStatus) {
                return back()->with('info', 'El estado del pedido no ha cambiado.');
            }

            $order->status = $request->status;
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $oldStatus,
                'to_status' => $request->status,
                'note' => 'Actualizado por administrador',
            ]);

            // ACTION 1: DEDUCT STOCK MOVEMENT (Pending -> Paid)
            // Stock was ALREADY decremented during reservation. Just record the AUDIT trail.
            if ($request->status === 'paid' && $oldStatus === 'pending') {
                foreach ($order->items as $item) {
                    try {
                        InventoryMovement::create([
                            'product_id' => $item->product_id,
                            'variant_id' => $item->variant_id,
                            'type' => 'out',
                            'quantity' => $item->quantity,
                            'reason' => 'Venta confirmada por administrador #' . $order->order_number,
                            'reference_type' => 'Order',
                            'reference_id' => $order->id,
                            'created_by' => auth()->id(),
                        ]);
                    } catch (\Illuminate\Database\QueryException $e) {
                        // Idempotency: Ignore duplicate movement if it already exists (e.g. MP webhook arrived just before)
                        if ($e->getCode() != 23000 && !str_contains($e->getMessage(), '19')) throw $e;
                    }
                }

                foreach($order->payments as $payment) {
                    if ($payment->status !== 'paid') {
                        $payment->status = 'paid';
                        $payment->paid_at = now();
                        $payment->save();
                    }
                }
            }

            // ACTION 2: RESTORE STOCK (Pending -> Cancelled/Refunded)
            // Restore the "reserved" stock back to available pool. No movement record needed for pending cleanup.
            if (in_array($request->status, ['cancelled', 'refunded']) && $oldStatus === 'pending') {
                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        ProductVariant::where('id', $item->variant_id)->increment('stock', $item->quantity);
                    } else {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }
            }

            // ACTION 3: RESTORE STOCK + RECORD ENTRY (Paid -> Cancelled/Refunded)
            // Stock was already moved "OUT". We need to "IN" it back and increment.
            if (in_array($request->status, ['cancelled', 'refunded']) && $oldStatus === 'paid' && $request->has('restore_stock')) {
                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        ProductVariant::where('id', $item->variant_id)->increment('stock', $item->quantity);
                    } else {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }

                    try {
                        InventoryMovement::create([
                            'product_id' => $item->product_id,
                            'variant_id' => $item->variant_id,
                            'type' => 'in',
                            'quantity' => $item->quantity,
                            'reason' => 'Restauración por cancelación de pedido pagado #' . $order->order_number,
                            'reference_type' => 'Order',
                            'reference_id' => $order->id,
                            'created_by' => auth()->id(),
                        ]);
                    } catch (\Illuminate\Database\QueryException $e) {
                         if ($e->getCode() != 23000 && !str_contains($e->getMessage(), '19')) throw $e;
                    }
                }
            }

            $message = 'Estado del pedido actualizado correctamente.';
            if ($request->status === 'paid' && $oldStatus !== 'paid') {
                $message = 'Pedido marcado como pagado e inventario descontado.';
            } elseif ($request->status === 'cancelled' && $oldStatus === 'paid' && $request->has('restore_stock')) {
                $message = 'Pedido cancelado e inventario devuelto.';
            }

            return back()->with('success', $message);
        });
    }

    public function addPayment(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $payment = new \App\Models\Payment();
        $payment->order_id = $order->id;
        $payment->method_id = $request->payment_method_id;
        $payment->amount = $request->amount;
        $payment->reference = $request->reference; // e.g., Auth Code or Manual Note
        $payment->status = 'paid'; // Manual payments are usually confirmed immediately
        $payment->paid_at = now();
        $payment->save();

        // Update Order Status based on Balance
        $totalPaid = $order->total_paid; // Uses the fresh calculation including the new payment?
        // Need to reload relations or trust the helper which queries DB?
        // Model attribute uses relationship query, so we should refresh or just query again.
        $totalPaid = $order->payments()->where('status', 'paid')->sum('amount');

        if ($totalPaid >= $order->total) {
            $order->status = 'paid';
        } elseif ($totalPaid > 0) {
            $order->status = 'partially_paid';
        }
        $order->save();

        return back()->with('success', 'Pago registrado correctamente.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('dashboard.orders.index')->with('success', 'Pedido eliminado correctamente.');
    }

    public function linkCustomer(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = \App\Models\Customer::findOrFail($request->customer_id);

        $order->update([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
        ]);

        return back()->with('success', 'Pedido vinculado al cliente correctamente.');
    }

    public function registerAsCustomer(Order $order)
    {
        if ($order->customer_id) {
            return back()->with('error', 'Este pedido ya está vinculado a un cliente.');
        }

        // Check for existing customer with same email or phone to avoid duplicates
        $existing = \App\Models\Customer::where('email', $order->customer_email)
            ->orWhere('phone', $order->customer_phone)
            ->first();

        if ($existing) {
            // If exists, just link it
            $order->update(['customer_id' => $existing->id]);
            return back()->with('success', 'Se encontró un cliente existente con estos datos y se ha vinculado el pedido.');
        }

        // Create new customer
        $customer = \App\Models\Customer::create([
            'name' => $order->customer_name,
            'email' => $order->customer_email,
            'phone' => $order->customer_phone,
        ]);

        $order->update(['customer_id' => $customer->id]);

        return back()->with('success', 'Cliente registrado y pedido vinculado correctamente.');
    }

    public function updateCustomer(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $order->update($request->only(['customer_name', 'customer_email', 'customer_phone']));

        return back()->with('success', 'Datos del cliente actualizados correctamente.');
    }

    public function destroyPayment(Order $order, $paymentId)
    {
        $payment = $order->payments()->findOrFail($paymentId);
        $payment->delete();

        // Recalculate Order Status
        $totalPaid = $order->payments()->where('status', 'paid')->sum('amount');
        if ($totalPaid >= $order->total) {
            $order->status = 'paid';
        } elseif ($totalPaid > 0) {
            $order->status = 'partially_paid';
        } else {
            $order->status = 'pending'; // Revert to pending if no payments left
        }
        $order->save();

        return back()->with('success', 'Pago eliminado correctamente.');
    }

    public function exportPaymentsPdf(Order $order)
    {
        $order->load(['customer', 'payments.method', 'items.product']);
        return view('admin.orders.payments-pdf', compact('order'));
    }
}
