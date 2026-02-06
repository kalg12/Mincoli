<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Customer;
use App\Models\SiteSetting;
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
        $query = Quotation::with(['customer', 'user'])
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

        // Métricas
        $stats = [
            'total_day' => Quotation::whereDate('created_at', today())->count(),
            'converted_day' => Quotation::whereDate('created_at', today())->where('status', 'converted')->count(),
            'potential_amount' => Quotation::where('status', 'sent')->sum('total'),
            'conversion_rate' => Quotation::count() > 0 
                ? round((Quotation::where('status', 'converted')->count() / Quotation::count()) * 100, 2)
                : 0,
        ];

        return view('pos.quotations.index', compact('quotations', 'stats'));
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
        return response()->json($quotation->load(['items.product', 'items.variant', 'customer', 'user']));
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
     * Eliminar cotización
     */
    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return back()->with('success', 'Cotización eliminada correctamente');
    }
}
