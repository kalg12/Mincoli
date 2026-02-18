<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\InventoryMovement;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
         $order = Order::with([
             'customer', 
             'items.product.images', 
             'items.payments' => function($query) {
                 $query->where('status', 'paid');
             },
             'payments.method', 
             'payments.orderItems',
             'statusHistory'
         ])->findOrFail($id);

         $productsForAdd = Product::query()
             ->select(['id', 'name', 'price', 'sale_price', 'stock'])
             ->with(['variants:id,product_id,name,price,sale_price,stock'])
             ->orderBy('name')
             ->get();

         return view('admin.orders.show', compact('order', 'productsForAdd'));
    }

    public function addItem(Request $request, Order $order)
    {
        if (in_array($order->status, ['cancelled', 'refunded'])) {
            return back()->with('error', 'No se pueden agregar productos a un pedido cancelado o reembolsado.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $order) {
            $order = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            $product = Product::findOrFail($request->product_id);
            $variant = null;

            if ($request->filled('variant_id')) {
                $variant = ProductVariant::where('id', $request->variant_id)
                    ->where('product_id', $product->id)
                    ->firstOrFail();
            }

            $quantity = (int) $request->quantity;

            $derivedPrice = $product->sale_price ?? $product->price;
            if ($variant) {
                $derivedPrice = $variant->sale_price ?? ($variant->price ?? $derivedPrice);
            }

            $unitPrice = $request->filled('unit_price') ? (float) $request->unit_price : (float) $derivedPrice;
            $subtotalItem = $unitPrice * $quantity;

            $applyIva = (float) $order->iva_total > 0;
            $ivaItem = $applyIva ? ($subtotalItem * 0.16) : 0;
            $totalItem = $subtotalItem + $ivaItem;

            $orderItem = new \App\Models\OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->variant_id = $variant?->id;
            $orderItem->quantity = $quantity;
            $orderItem->unit_price = $unitPrice;
            $orderItem->iva_amount = $ivaItem;
            $orderItem->total = $totalItem;
            $orderItem->status = 'pending';
            $orderItem->save();

            // Reserva/descuento de stock atómico
            if ($variant) {
                $affected = DB::table('product_variants')
                    ->where('id', $variant->id)
                    ->where('stock', '>=', $quantity)
                    ->decrement('stock', $quantity);
            } else {
                $affected = DB::table('products')
                    ->where('id', $product->id)
                    ->where('stock', '>=', $quantity)
                    ->decrement('stock', $quantity);
            }

            if ($affected === 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'No hay stock suficiente para agregar este producto.',
                ]);
            }

            // Actualizar totales del pedido
            $order->subtotal = (float) $order->subtotal + $subtotalItem;
            $order->iva_total = (float) $order->iva_total + $ivaItem;
            $order->total = (float) $order->total + $totalItem;
            $order->save();

            // Mantener consistencia básica del estado (sin pisar shipped/delivered)
            if (in_array($order->status, ['pending', 'paid', 'partially_paid'])) {
                $totalPaid = $order->payments()->where('status', 'paid')->sum('amount');
                if ($totalPaid >= $order->total) {
                    $order->status = 'paid';
                } elseif ($totalPaid > 0) {
                    $order->status = 'partially_paid';
                } else {
                    $order->status = 'pending';
                }
                $order->save();
            }

            // Registrar movimiento para auditar el agregado
            try {
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'type' => 'out',
                    'quantity' => $quantity,
                    'reason' => 'Producto agregado a pedido #' . $order->order_number,
                    'reference_type' => 'OrderItemAdded',
                    'reference_id' => $orderItem->id,
                    'created_by' => Auth::id(),
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() != 23000 && !str_contains($e->getMessage(), '19')) {
                    throw $e;
                }
            }

            return back()->with('success', 'Producto agregado al pedido correctamente.');
        });
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

            $statusHistory = OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $oldStatus,
                'to_status' => $request->status,
                'note' => 'Actualizado por administrador',
            ]);

            $movementGroups = $this->buildMovementGroups($order);

             // ACTION 1: DEDUCT STOCK MOVEMENT (Pending -> Paid)
             // Stock was ALREADY decremented during reservation. Just record the AUDIT trail.
             // NOTE: Changed to NOT record inventory movements when marking as paid
             if ($request->status === 'paid' && $oldStatus === 'pending') {
                 // No longer record inventory movements when just changing status to paid
                 // This prevents creating movements in /dashboard/inventory/movements
                 // when only the payment status is updated
                 
                 foreach($order->payments as $payment) {
                     if ($payment->status !== 'paid') {
                         $payment->status = 'paid';
                         $payment->paid_at = now();
                         $payment->save();
                     }
                 }
             }

            // ACTION 2: RESTORE STOCK + RECORD ENTRY (Cancel/Refund)
            // For pending, stock was reserved. For paid/shipped/delivered, stock was already out.
            $shouldRestoreStock = $oldStatus === 'pending' || $request->has('restore_stock');
            if (in_array($request->status, ['cancelled', 'refunded']) && $shouldRestoreStock) {
                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        ProductVariant::where('id', $item->variant_id)->increment('stock', $item->quantity);
                    } else {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }

                if (!$this->orderMovementExistsForStatus($statusHistory->id, $movementGroups, 'in')) {
                    $reason = match ($oldStatus) {
                        'pending' => 'Cancelación de pedido pendiente #' . $order->order_number,
                        'paid', 'partially_paid' => 'Restauración por cancelación de pedido pagado #' . $order->order_number,
                        'shipped' => 'Restauración por cancelación de pedido enviado #' . $order->order_number,
                        'delivered' => 'Restauración por cancelación de pedido entregado #' . $order->order_number,
                        default => 'Restauración de inventario por cancelación #' . $order->order_number,
                    };

                    $this->recordOrderMovementForStatus(
                        $order,
                        $movementGroups,
                        'in',
                        $reason,
                        Auth::id(),
                        $statusHistory->id
                    );
                }
            }

            // ACTION 1B: RECORD MOVEMENT WHEN SHIPPING/DELIVERING (if not already recorded)
            // Some orders go directly to shipped/delivered without passing through paid.
            if (in_array($request->status, ['shipped', 'delivered']) && !$this->orderMovementExistsForStatus($statusHistory->id, $movementGroups, 'out')) {
                $this->recordOrderMovementForStatus(
                    $order,
                    $movementGroups,
                    'out',
                    ($request->status === 'shipped'
                        ? 'Pedido enviado #' . $order->order_number
                        : 'Pedido entregado #' . $order->order_number),
                    Auth::id(),
                    $statusHistory->id
                );
            }

             $message = 'Estado del pedido actualizado correctamente.';
             if ($request->status === 'paid' && $oldStatus !== 'paid') {
                 $message = 'Pedido marcado como pagado.';
             } elseif ($request->status === 'cancelled' && $oldStatus === 'paid' && $request->has('restore_stock')) {
                 $message = 'Pedido cancelado e inventario devuelto.';
             }

            return back()->with('success', $message);
        });
    }

    private function buildMovementGroups(Order $order): array
    {
        $groups = [];

        foreach ($order->items as $item) {
            $variantId = $item->variant_id ?? 0;
            $key = $item->product_id . '|' . $variantId;

            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => 0,
                ];
            }

            $groups[$key]['quantity'] += $item->quantity;
        }

        return array_values($groups);
    }

    private function orderMovementExistsForOrder(Order $order, array $movementGroups, string $type): bool
    {
        $referenceTypes = collect($movementGroups)
            ->map(fn ($group) => 'OrderMovement:' . $order->id . ':product:' . $group['product_id'] . ':variant:' . ($group['variant_id'] ?? 0))
            ->all();

        return InventoryMovement::where('type', $type)
            ->where('reference_id', $order->id)
            ->whereIn('reference_type', $referenceTypes)
            ->exists();
    }

    private function orderMovementExistsForStatus(int $statusHistoryId, array $movementGroups, string $type): bool
    {
        $referenceTypes = collect($movementGroups)
            ->map(fn ($group) => 'OrderStatusHistory:' . $statusHistoryId . ':product:' . $group['product_id'] . ':variant:' . ($group['variant_id'] ?? 0))
            ->all();

        return InventoryMovement::where('type', $type)
            ->where('reference_id', $statusHistoryId)
            ->whereIn('reference_type', $referenceTypes)
            ->exists();
    }

    private function recordOrderMovementForOrder(Order $order, array $movementGroups, string $type, string $reason, ?int $userId): void
    {
        foreach ($movementGroups as $group) {
            try {
                InventoryMovement::create([
                    'product_id' => $group['product_id'],
                    'variant_id' => $group['variant_id'],
                    'type' => $type,
                    'quantity' => $group['quantity'],
                    'reason' => $reason,
                    'reference_type' => 'OrderMovement:' . $order->id . ':product:' . $group['product_id'] . ':variant:' . ($group['variant_id'] ?? 0),
                    'reference_id' => $order->id,
                    'created_by' => $userId,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() != 23000 && !str_contains($e->getMessage(), '19')) throw $e;
            }
        }
    }

    private function recordOrderMovementForStatus(Order $order, array $movementGroups, string $type, string $reason, ?int $userId, int $statusHistoryId): void
    {
        foreach ($movementGroups as $group) {
            try {
                InventoryMovement::create([
                    'product_id' => $group['product_id'],
                    'variant_id' => $group['variant_id'],
                    'type' => $type,
                    'quantity' => $group['quantity'],
                    'reason' => $reason,
                    'reference_type' => 'OrderStatusHistory:' . $statusHistoryId . ':product:' . $group['product_id'] . ':variant:' . ($group['variant_id'] ?? 0),
                    'reference_id' => $statusHistoryId,
                    'created_by' => $userId,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() != 23000 && !str_contains($e->getMessage(), '19')) throw $e;
            }
        }
    }

    public function addPayment(Request $request, Order $order)
    {
        // Cuando es abono general no validar/usan allocations (los inputs ocultos pueden enviar valores vacíos)
        if ($request->input('allocation_type') !== 'specific') {
            $request->merge(['allocations' => []]);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'reference' => 'nullable|string',
            'transfer_number' => 'nullable|string|max:255',
            'capture_line' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'allocation_type' => 'nullable|in:general,specific',
            'allocations' => 'nullable|array',
            'allocations.*.order_item_id' => 'required_with:allocations|exists:order_items,id',
            'allocations.*.amount' => 'nullable|numeric|min:0',
        ], [
            'amount.required' => 'El monto del pago es obligatorio.',
            'amount.numeric' => 'El monto debe ser un número.',
            'amount.min' => 'El monto debe ser mayor a 0.',
            'payment_method_id.required' => 'Debes elegir un método de pago.',
            'payment_method_id.exists' => 'El método de pago seleccionado no es válido.',
            'allocations.*.order_item_id.required_with' => 'Al asignar a productos específicos, cada fila debe tener un producto válido.',
            'allocations.*.order_item_id.exists' => 'Uno de los productos seleccionados no pertenece a este pedido.',
            'allocations.*.amount.required_with' => 'Al asignar a productos específicos, indica el monto por producto (o 0 si no aplica).',
            'allocations.*.amount.numeric' => 'El monto asignado debe ser un número.',
            'allocations.*.amount.min' => 'El monto asignado no puede ser negativo.',
        ]);

        $payment = new \App\Models\Payment();
        $payment->order_id = $order->id;
        $payment->method_id = $request->payment_method_id;
        $payment->amount = $request->amount;
        $payment->reference = $request->reference;
        $payment->transfer_number = $request->transfer_number;
        $payment->capture_line = $request->capture_line;
        $payment->status = 'paid';
        $payment->paid_at = now();
        $payment->save();

        // Assign payment to specific order items if provided
        if ($request->allocation_type === 'specific' && $request->has('allocations')) {
            $totalAllocated = 0;
            foreach ($request->allocations as $allocation) {
                if (isset($allocation['order_item_id']) && isset($allocation['amount']) && $allocation['amount'] > 0) {
                    $orderItem = $order->items()->find($allocation['order_item_id']);
                    if ($orderItem) {
                        // Don't allow allocating more than the item's remaining amount
                        $maxAmount = min($allocation['amount'], $orderItem->remaining, $request->amount - $totalAllocated);
                        if ($maxAmount > 0) {
                            $payment->orderItems()->attach($orderItem->id, ['amount' => $maxAmount]);
                            $totalAllocated += $maxAmount;
                        }
                    }
                }
            }
        }

        // Update Order Status based on Balance
        $totalPaid = $order->payments()->where('status', 'paid')->sum('amount');

        if ($totalPaid >= $order->total) {
            $order->status = 'paid';
        } elseif ($totalPaid > 0) {
            $order->status = 'partially_paid';
        }
        $order->save();

        return back()->with('success', 'Pago registrado correctamente.');
    }

    public function updateItemStatus(Request $request, Order $order, $itemId)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready_to_ship,shipped,delivered,cancelled',
        ]);

        $item = $order->items()->findOrFail($itemId);
        $item->update(['status' => $request->status]);

        return back()->with('success', 'Estado del producto actualizado correctamente.');
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
