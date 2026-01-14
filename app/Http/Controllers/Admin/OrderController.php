<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
        $order = Order::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,paid,shipped,delivered,cancelled,refunded']);
        
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        if ($oldStatus !== $request->status) {
            // Log history if table exists or just save
            // Assuming simple update for now, ideally record history
        }

        // Also update payment status if Order is marked as Paid
        if ($request->status === 'paid') {
            foreach($order->payments as $payment) {
                if ($payment->status !== 'paid') {
                    $payment->status = 'paid';
                    $payment->paid_at = now();
                    $payment->save();
                }
            }
        }

        return back()->with('success', 'Estado del pedido actualizado correctamente.');
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
