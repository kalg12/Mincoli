<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Customer;
use App\Models\SiteSetting;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuotationController extends Controller
{
    /**
     * Listado de cotizaciones con filtros y paginación
     */
    public function index(Request $request): View
    {
        $query = Quotation::with(['customer', 'user', 'items.product', 'items.variant'])
            ->whereNull('deleted_at')
            ->latest();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('folio', 'like', "%$search%")
                  ->orWhere('customer_name', 'like', "%$search%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_range')) {
            $range = $request->date_range;
            $today = now()->startOfDay();
            switch ($range) {
                case 'today':
                    $query->whereDate('created_at', $today);
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', now()->subDay()->startOfDay());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
            }
        }

        if ($request->filled('min_amount')) {
            $query->where('total', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('total', '<=', $request->max_amount);
        }

        $perPage = $request->get('per_page', 10);
        $quotations = $query->paginate($perPage)->withQueryString();

        // Métricas (excluyendo eliminadas)
        $stats = [
            'total_day' => Quotation::whereNull('deleted_at')->whereDate('created_at', today())->count(),
            'converted_day' => Quotation::whereNull('deleted_at')->whereDate('created_at', today())->where('status', 'converted')->count(),
            'potential_amount' => Quotation::whereNull('deleted_at')->where('status', 'sent')->sum('total'),
            'conversion_rate' => Quotation::whereNull('deleted_at')->count() > 0 
                ? round((Quotation::whereNull('deleted_at')->where('status', 'converted')->count() / Quotation::whereNull('deleted_at')->count()) * 100, 2)
                : 0,
        ];

        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('pos.quotations.index', compact('quotations', 'stats', 'paymentMethods'));
    }

    /**
     * Guardar cotización desde el POS
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'share_type' => 'nullable|string|in:whatsapp,image,pdf,link',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $ivaTotal = 0;
            $showIva = (bool) SiteSetting::get('store', 'show_iva', true);

            foreach ($validated['items'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            if ($showIva) {
                $ivaTotal = $subtotal * 0.16 / 1.16;
                $actualSubtotal = $subtotal - $ivaTotal;
            } else {
                $actualSubtotal = $subtotal;
            }

            $quotation = Quotation::create([
                'user_id' => Auth::id(),
                'customer_id' => $validated['customer_id'],
                'customer_name' => $validated['customer_name'] ?? ($validated['customer_id'] ? Customer::find($validated['customer_id'])->name : 'Público General'),
                'customer_phone' => $validated['customer_phone'] ?? ($validated['customer_id'] ? Customer::find($validated['customer_id'])->phone : null),
                'subtotal' => $actualSubtotal,
                'iva_total' => $ivaTotal,
                'total' => $subtotal,
                'status' => 'sent',
                'share_type' => $validated['share_type'] ?? 'whatsapp',
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $itemIva = $showIva ? ($itemTotal * 0.16 / 1.16) : 0;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $item['id'],
                    'variant_id' => $item['variant'] ? $item['variant']['id'] : null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'iva_amount' => $itemIva,
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cotización registrada correctamente',
                'quotation' => $quotation->load('items'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la cotización: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver detalle de cotización
     */
    public function show(Quotation $quotation)
    {
        $quotation->load(['items.product', 'items.variant', 'customer', 'user']);
        
        // Si es una petición AJAX o espera JSON, devolver JSON
        $acceptHeader = request()->header('Accept', '');
        $isJsonRequest = request()->expectsJson() 
            || request()->ajax() 
            || request()->wantsJson()
            || str_contains($acceptHeader, 'application/json')
            || request()->header('X-Requested-With') === 'XMLHttpRequest';
        
        if ($isJsonRequest) {
            return response()->json([
                'id' => $quotation->id,
                'folio' => $quotation->folio,
                'customer_id' => $quotation->customer_id,
                'customer' => $quotation->customer,
                'customer_name' => $quotation->customer_name,
                'customer_phone' => $quotation->customer_phone,
                'subtotal' => $quotation->subtotal,
                'iva_total' => $quotation->iva_total,
                'total' => $quotation->total,
                'status' => $quotation->status,
                'items' => $quotation->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total' => $item->total,
                        'product' => $item->product,
                        'variant' => $item->variant,
                    ];
                }),
            ]);
        }
        
        // Si no, devolver la vista
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('pos.quotations.show', compact('quotation', 'paymentMethods'));
    }

    /**
     * Cambiar estado
     */
    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:draft,sent,accepted,rejected,expired,converted',
            'internal_notes' => 'nullable|string',
        ]);

        $quotation->update($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar cotización (soft delete)
     */
    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return back()->with('success', 'Cotización movida a la papelera');
    }

    /**
     * Restaurar cotización
     */
    public function restore($id)
    {
        $quotation = Quotation::withTrashed()->findOrFail($id);
        $quotation->restore();
        return back()->with('success', 'Cotización restaurada correctamente');
    }

    /**
     * Eliminar permanentemente
     */
    public function forceDelete($id)
    {
        $quotation = Quotation::withTrashed()->findOrFail($id);
        $quotation->forceDelete();
        return back()->with('success', 'Cotización eliminada permanentemente');
    }

    /**
     * Ver papelera (cotizaciones eliminadas)
     */
    public function trash(Request $request): View
    {
        $query = Quotation::with(['customer', 'user', 'items.product', 'items.variant'])
            ->onlyTrashed()
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('folio', 'like', "%$search%")
                  ->orWhere('customer_name', 'like', "%$search%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $quotations = $query->paginate($perPage)->withQueryString();

        return view('pos.quotations.trash', compact('quotations'));
    }

    /**
     * Editar cotización
     */
    public function edit(Quotation $quotation)
    {
        $quotation->load(['items.product', 'items.variant', 'customer', 'user']);
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        
        // Cargar categorías para la búsqueda de productos
        $categories = \App\Models\Category::whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get();
        
        // Cargar productos activos con variantes e imágenes
        $products = Product::with(['variants', 'images', 'category'])
            ->where('is_active', true)
            ->latest()
            ->get();
        
        $customers = Customer::orderBy('name')->get();
        
        // Preparar items para JavaScript
        $cartItems = $quotation->items->map(function($item) {
            $variant = null;
            if ($item->variant) {
                $variant = ['id' => $item->variant_id, 'name' => $item->variant->name];
            }
            return [
                'id' => $item->product_id,
                'name' => $item->product ? $item->product->name : 'Producto',
                'price' => (float) $item->unit_price,
                'quantity' => (int) $item->quantity,
                'variant' => $variant
            ];
        })->values()->all();
        
        return view('pos.quotations.edit', compact('quotation', 'paymentMethods', 'products', 'customers', 'categories', 'cartItems'));
    }

    /**
     * Actualizar cotización
     */
    public function update(Request $request, Quotation $quotation)
    {
        // Preparar datos antes de validar
        $items = $request->input('items', []);
        foreach ($items as $key => $item) {
            if (empty($item['variant_id']) || $item['variant_id'] === '') {
                $items[$key]['variant_id'] = null;
            }
        }
        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $ivaTotal = 0;
            $showIva = (bool) SiteSetting::get('store', 'show_iva', true);

            foreach ($validated['items'] as $item) {
                $subtotal += $item['unit_price'] * $item['quantity'];
            }

            if ($showIva) {
                $ivaTotal = $subtotal * 0.16 / 1.16;
                $actualSubtotal = $subtotal - $ivaTotal;
            } else {
                $actualSubtotal = $subtotal;
            }

            // Actualizar información del cliente
            if ($validated['customer_id']) {
                $customer = Customer::find($validated['customer_id']);
                $quotation->update([
                    'customer_id' => $validated['customer_id'],
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone,
                    'subtotal' => $actualSubtotal,
                    'iva_total' => $ivaTotal,
                    'total' => $subtotal,
                    'notes' => $validated['notes'] ?? null,
                ]);
            } else {
                $quotation->update([
                    'customer_id' => null,
                    'customer_name' => $validated['customer_name'] ?? 'Público General',
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'subtotal' => $actualSubtotal,
                    'iva_total' => $ivaTotal,
                    'total' => $subtotal,
                    'notes' => $validated['notes'] ?? null,
                ]);
            }

            // Eliminar items existentes
            $quotation->items()->delete();

            // Crear nuevos items
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['unit_price'] * $item['quantity'];
                $itemIva = $showIva ? ($itemTotal * 0.16 / 1.16) : 0;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'iva_amount' => $itemIva,
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();

            return redirect()->route('dashboard.pos.quotations.show', $quotation->id)
                ->with('success', 'Cotización actualizada correctamente. Puedes compartirla ahora.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating quotation: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error al actualizar la cotización: ' . $e->getMessage())
                ->withInput();
        }
    }
}
