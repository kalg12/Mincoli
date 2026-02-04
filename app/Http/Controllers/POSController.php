<?php

namespace App\Http\Controllers;

use App\Models\POSSession;
use App\Models\POSTransaction;
use App\Models\POSTransactionItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\InventoryMovement;
use App\Models\PaymentMethod;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    /**
     * Dashboard de POS
     */
    public function index(Request $request): View
    {
        // Obtener categorías con sus subcategorías (incluyendo ocultas para POS)
        $categories = \App\Models\Category::whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get();
        
        // Obtener el número de productos por página desde la solicitud, default 30
        $perPage = $request->get('per_page', 30);
        
        // Validar que el per_page sea un valor permitido
        $allowedPerPage = [10, 20, 30, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 30;
        }
        
        // Cargar productos con sus variantes e imagenes para el grid inicial (incluyendo inactivos para POS)
        $products = Product::with(['variants', 'images', 'category'])
            ->latest()
            ->paginate($perPage);
            
        // Transformar los productos para incluir image_url
        $products->getCollection()->transform(function ($product) {
            $product->image_url = $product->images->first()?->url 
                ?? 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=27272a&color=a1a1aa&size=256';
            return $product;
        });

        $stats = [
            'today_sales' => POSTransaction::whereDate('created_at', today())
                ->where('status', '!=', 'cancelled')
                ->sum('total'),
            'today_transactions' => POSTransaction::whereDate('created_at', today())
                ->where('status', '!=', 'cancelled')
                ->count(),
        ];

        $showIva = (bool) SiteSetting::get('store', 'show_iva', true);

        return view('pos.index', [
            'categories' => $categories,
            'products' => $products,
            'stats' => $stats,
            'showIva' => $showIva,
            'perPage' => $perPage,
        ]);
    }

    // ... (transactions method skipped) ...

    /**
     * Buscar productos por SKU/Barcode
     */
    public function searchProduct(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category_id');
        $subcategoryId = $request->get('subcategory_id');
        $perPage = $request->get('per_page', 30);

        $products = Product::when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($subcategoryId, fn($q) => $q->where('subcategory_id', $subcategoryId))
            ->where(function ($q) use ($query) {
                $q->where('sku', 'like', "%$query%")
                    ->orWhere('barcode', 'like', "%$query%")
                    ->orWhere('name', 'like', "%$query%");
            })
            ->with(['variants', 'images'])
            ->paginate($perPage);
            
        // Append image_url accessor
        $products->getCollection()->each(function($product) {
            $product->append('image_url');
        });

        return response()->json($products);
    }

    /**
     * Agregar item a transaccion
     */
    public function addItem(Request $request, POSTransaction $transaction)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:1000',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $variant = null;

        if ($validated['product_variant_id']) {
            $variant = $product->variants()->findOrFail($validated['product_variant_id']);
        }

        $showIva = (bool) SiteSetting::get('store', 'show_iva', true);
        // Usar precio de variante si existe, sino precio del producto
        $unitPrice = $variant?->price ?? $product->sale_price ?? $product->price;
        $ivaRate = $showIva ? ($product->iva_rate ?? 0) : 0;

        $subtotal = $unitPrice * $validated['quantity'];
        $ivaAmount = $showIva ? $subtotal * ($ivaRate / 100) : 0;
        $total = $subtotal + $ivaAmount;

        $item = $transaction->items()->create([
            'product_id' => $validated['product_id'],
            'product_variant_id' => $validated['product_variant_id'] ?? null,
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'iva_rate' => $ivaRate,
            'subtotal' => $subtotal,
            'iva_amount' => $ivaAmount,
            'total' => $total,
            'status' => 'reserved',
        ]);

        $this->updateTransactionTotals($transaction);

        return response()->json($item->load(['product', 'variant']), 201);
    }

    /**
     * Remover item de transaccion
     */
    public function removeItem(POSTransaction $transaction, POSTransactionItem $item)
    {
        if ($item->pos_transaction_id !== $transaction->id) {
            abort(403);
        }

        $item->delete();
        $this->updateTransactionTotals($transaction);

        return response()->json(['success' => true]);
    }

    /**
     * Actualizar cantidad de item
     */
    public function updateItemQuantity(Request $request, POSTransaction $transaction, POSTransactionItem $item)
    {
        if ($item->pos_transaction_id !== $transaction->id) {
            abort(403);
        }

        $validated = $request->validate(['quantity' => 'required|integer|min:1|max:1000']);

        $showIva = (bool) SiteSetting::get('store', 'show_iva', true);
        $subtotal = $item->unit_price * $validated['quantity'];
        $ivaAmount = $showIva ? $subtotal * ($item->iva_rate / 100) : 0;

        $item->update([
            'quantity' => $validated['quantity'],
            'subtotal' => $subtotal,
            'iva_amount' => $ivaAmount,
            'total' => $subtotal + $ivaAmount,
            'iva_rate' => $showIva ? $item->iva_rate : 0,
        ]);

        $this->updateTransactionTotals($transaction);

        return response()->json($item);
    }

    /**
     * Registrar pago/abono
     */
    public function recordPayment(Request $request, POSTransaction $transaction)
    {
        $validated = $request->validate([
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'nullable|in:pending,completed',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = $validated['status'] ?? 'completed';
        $validated['paid_at'] = $validated['status'] === 'completed' ? now() : null;

        $transaction->payments()->create($validated);

        // Actualizar estado de pago
        $totalPaid = $transaction->payments()
            ->where('status', 'completed')
            ->sum('amount');

        if ($totalPaid >= $transaction->total) {
            $transaction->update(['payment_status' => 'completed']);
        } elseif ($totalPaid > 0) {
            $transaction->update(['payment_status' => 'partial']);
        } else {
            $transaction->update(['payment_status' => 'pending']);
        }

        return response()->json(['success' => true, 'total_paid' => $totalPaid]);
    }

    /**
     * Imprimir ticket
     */
    public function printTicket(POSTransaction $transaction)
    {
        $showIva = (bool) SiteSetting::get('store', 'show_iva', true);

        return view('pos.ticket', [
            'transaction' => $transaction->load(['items.product', 'items.variant', 'customer', 'payments']),
            'showIva' => $showIva,
        ]);
    }

    /**
     * Completar transaccion
     */
    public function completeTransaction(POSTransaction $transaction)
    {
        $transaction->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Actualizar items a pending_shipment
        $transaction->items()->update(['status' => 'pending_shipment']);

        return redirect()->route('dashboard.pos.index')->with('success', 'Apartado completado');
    }

    /**
     * Cerrar sesion
     */
    public function closeSession(POSSession $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        $session->update([
            'status' => 'closed',
            'closed_at' => now(),
            'total_sales' => $session->transactions()
                ->where('status', '!=', 'cancelled')
                ->sum('total'),
            'total_payments' => $session->transactions()
                ->sum(DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM pos_payments WHERE pos_payments.pos_transaction_id = pos_transactions.id AND status = "completed")')),
        ]);

        return redirect()->route('dashboard.pos.index')->with('success', 'Sesion cerrada');
    }

    /**
     * Ver items pendientes por enviar
     */
    public function pendingShipments(): View
    {
        $items = POSTransactionItem::where('status', 'pending_shipment')
            ->with(['posTransaction.customer', 'product', 'variant'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('pos.pending-shipments', ['items' => $items]);
    }

    /**
     * Marcar item como enviado
     */
    public function markAsShipped(POSTransactionItem $item)
    {
        $item->update(['status' => 'shipped']);

        return back()->with('success', 'Item marcado como enviado');
    }

    /**
     * Marcar item como entregado
     */
    public function markAsCompleted(POSTransactionItem $item)
    {
        $item->update(['status' => 'completed']);

        return back()->with('success', 'Item marcado como completado');
    }

    /**
     * Actualizar totales de transaccion
     */
    private function updateTransactionTotals(POSTransaction $transaction): void
    {
        $showIva = (bool) SiteSetting::get('store', 'show_iva', true);

        // Si IVA esta deshabilitado, normalizar items
        $items = $transaction->items()->get();
        if (!$showIva) {
            $items->each(function ($item) {
                $subtotal = $item->unit_price * $item->quantity;
                $item->update([
                    'iva_rate' => 0,
                    'iva_amount' => 0,
                    'total' => $subtotal,
                    'subtotal' => $subtotal,
                ]);
            });
        }

        $subtotal = $transaction->items()->sum(DB::raw('unit_price * quantity'));
        $ivaTotal = $showIva ? $transaction->items()->sum('iva_amount') : 0;
        $total = $subtotal + $ivaTotal;

        $transaction->update([
            'subtotal' => $subtotal,
            'iva_total' => $ivaTotal,
            'total' => $total,
        ]);
    }

    /**
     * Buscar clientes por telefono/nombre
     */
    public function storeAjax(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'payment_status' => 'nullable|in:paid,pending',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $items = $request->items;

            foreach ($items as $item) {
                // Verificar stock antes de procesar
                if (isset($item['variant']) && $item['variant']) {
                    $variant = ProductVariant::find($item['variant']['id']);
                    if (!$variant || $variant->stock < $item['quantity']) {
                        throw new \Exception("Stock insuficiente para: {$item['name']} ({$item['variant']['name']})");
                    }
                } else {
                    $product = Product::find($item['id']);
                    if (!$product || $product->stock < $item['quantity']) {
                        throw new \Exception("Stock insuficiente para: {$item['name']}");
                    }
                }

                $subtotal += $item['price'] * $item['quantity'];
            }

            $total = $subtotal; 
            $showIva = (bool) SiteSetting::get('store', 'show_iva', true);
            $iva = $showIva ? ($total * 0.16 / 1.16) : 0;
            $calculatedSubtotal = $showIva ? ($subtotal / 1.16) : $subtotal;

            $status = $request->payment_status ?? 'paid';

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'subtotal' => $calculatedSubtotal,
                'iva_total' => $iva,
                'total' => $total,
                'channel' => 'pos',
                'status' => $status,
                'placed_at' => now(),
                'notes' => 'Pedido generado desde el POS. Estado: ' . ($status == 'paid' ? 'Pagado' : 'Pendiente'),
            ]);

            foreach ($items as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $itemIvaAmount = $showIva ? ($itemTotal * 0.16 / 1.16) : 0;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'variant_id' => $item['variant'] ? $item['variant']['id'] : null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'iva_amount' => $itemIvaAmount,
                    'total' => $itemTotal,
                ]);

                // Descontar stock y registrar movimiento
                if ($item['variant']) {
                    $variant = ProductVariant::find($item['variant']['id']);
                    $variant->decrement('stock', $item['quantity']);
                    
                    InventoryMovement::create([
                        'product_id' => $item['id'],
                        'variant_id' => $item['variant']['id'],
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'reason' => "Venta POS - Orden #{$order->order_number} ({$status})",
                        'reference_type' => 'Order',
                        'reference_id' => $order->id,
                        'created_by' => Auth::id(),
                    ]);
                } else {
                    $product = Product::find($item['id']);
                    $product->decrement('stock', $item['quantity']);

                    InventoryMovement::create([
                        'product_id' => $item['id'],
                        'variant_id' => null,
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'reason' => "Venta POS - Orden #{$order->order_number} ({$status})",
                        'reference_type' => 'Order',
                        'reference_id' => $order->id,
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orden generada con éxito',
                'order_number' => $order->order_number,
                'id' => $order->id,
                'redirect_url' => route('dashboard.pos.success', $order->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function searchCustomer(Request $request)
    {
        $query = $request->get('q');
        $phone = $request->get('phone');

        $customers = Customer::query()
            ->when($phone, fn($q) => $q->where('phone', 'like', $phone . '%'))
            ->when($query, fn($q) => $q->orWhere('name', 'like', '%' . $query . '%'))
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'phone', 'email']);

        return response()->json($customers);
    }

    public function success(Order $order): View
    {
        return view('pos.success', compact('order'));
    }

    public function printOrderTicket(Order $order)
    {
        $showIva = (bool) SiteSetting::get('store', 'show_iva', true);
        
        return view('pos.ticket_order', [
            'order' => $order->load(['items.product', 'items.variant']),
            'showIva' => $showIva,
        ]);
    }
}
