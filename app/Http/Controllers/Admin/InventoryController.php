<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;

class InventoryController extends Controller
{
    // Dashboard de inventario
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_stock' => Product::sum('stock'),
            'low_stock_count' => Product::where('stock', '<=', 5)->count(),
            'out_of_stock_count' => Product::where('stock', 0)->count(),
            'total_value' => Product::sum(DB::raw('stock * price')),
        ];

        $recentMovements = InventoryMovement::with(['product', 'variant', 'createdBy'])
            ->latest()
            ->take(10)
            ->get();

        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();

        return view('admin.inventory.index', compact('stats', 'recentMovements', 'lowStockProducts'));
    }

    // Lista de movimientos
    public function movements(Request $request)
    {
        $query = InventoryMovement::with(['product', 'variant', 'createdBy']);

        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $movements = $query->latest()->paginate(20);

        return view('admin.inventory.movements', compact('movements'));
    }

    // Formulario para registrar movimiento
    public function createMovement()
    {
        $products = Product::with('variants')->where('is_active', true)->get();
        return view('admin.inventory.create-movement', compact('products'));
    }

    // Guardar movimiento
    public function storeMovement(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'type' => 'required|in:in,out,adjust',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            /** @var Guard $auth */
            $auth = auth();
            $movement = InventoryMovement::create([
                ...$validated,
                'created_by' => $auth->id(),
            ]);

            // Actualizar stock
            if ($validated['variant_id']) {
                $variant = ProductVariant::findOrFail($validated['variant_id']);
                $change = match ($validated['type']) {
                    'in' => $validated['quantity'],
                    'out' => -$validated['quantity'],
                    'adjust' => $validated['quantity'] - $variant->stock,
                };
                $variant->increment('stock', $change);
            } else {
                $product = Product::findOrFail($validated['product_id']);
                $change = match ($validated['type']) {
                    'in' => $validated['quantity'],
                    'out' => -$validated['quantity'],
                    'adjust' => $validated['quantity'] - $product->stock,
                };
                $product->increment('stock', $change);
            }
        });

        return redirect()->route('dashboard.inventory.movements')
            ->with('success', 'Movimiento registrado correctamente');
    }

    // ===== CONTEOS DE INVENTARIO =====

    // Lista de conteos
    public function counts()
    {
        $counts = InventoryCount::with('createdBy')
            ->latest()
            ->paginate(15);

        return view('admin.inventory.counts.index', compact('counts'));
    }

    // Crear nuevo conteo
    public function createCount()
    {
        return view('admin.inventory.counts.create');
    }

    // Guardar nuevo conteo
    public function storeCount(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        /** @var Guard $auth */
        $auth = auth();
        $count = InventoryCount::create([
            ...$validated,
            'status' => 'draft',
            'created_by' => $auth->id(),
        ]);

        return redirect()->route('dashboard.inventory.counts.show', $count)
            ->with('success', 'Conteo creado correctamente');
    }

    // Ver detalles del conteo
    public function showCount(InventoryCount $count)
    {
        $count->load(['items.product', 'items.variant', 'items.countedBy', 'createdBy', 'reviewedBy']);

        $stats = [
            'total_items' => $count->items->count(),
            'counted_items' => $count->items->whereNotNull('counted_quantity')->count(),
            'total_difference' => $count->items->sum('difference'),
            'missing_items' => $count->items->where('difference', '<', 0)->count(),
            'excess_items' => $count->items->where('difference', '>', 0)->count(),
        ];

        return view('admin.inventory.counts.show', compact('count', 'stats'));
    }

    // Iniciar conteo (genera items con stock actual)
    public function startCount(InventoryCount $count)
    {
        if ($count->status !== 'draft') {
            return back()->with('error', 'El conteo ya fue iniciado');
        }

        DB::transaction(function () use ($count) {
            // Agregar productos simples
            Product::where('is_active', true)
                ->whereDoesntHave('variants')
                ->get()
                ->each(function ($product) use ($count) {
                    InventoryCountItem::create([
                        'inventory_count_id' => $count->id,
                        'product_id' => $product->id,
                        'variant_id' => null,
                        'system_quantity' => $product->stock,
                    ]);
                });

            // Agregar variantes
            ProductVariant::whereHas('product', function ($q) {
                $q->where('is_active', true);
            })->get()->each(function ($variant) use ($count) {
                InventoryCountItem::create([
                    'inventory_count_id' => $count->id,
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'system_quantity' => $variant->stock,
                ]);
            });

            $count->update([
                'status' => 'in_progress',
                'started_at' => now(),
                'public_token' => $count->public_token ?: bin2hex(random_bytes(12)),
                'public_capture_enabled' => true,
            ]);
        });

        return back()->with('success', 'Conteo iniciado. Los productos han sido cargados.');
    }

    // Formulario de captura (para personal externo)
    public function captureForm(InventoryCount $count)
    {
        if ($count->status !== 'in_progress') {
            abort(403, 'Este conteo no está disponible para captura');
        }

        $items = $count->items()
            ->with(['product', 'variant'])
            ->paginate(50);

        return view('admin.inventory.counts.capture', compact('count', 'items'));
    }

    // Guardar captura individual
    public function saveCapture(Request $request, InventoryCount $count, InventoryCountItem $item)
    {
        $validated = $request->validate([
            'counted_quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        /** @var Guard $auth */
        $auth = auth();
        $item->update([
            ...$validated,
            'counted_by' => $auth->id(),
            'counted_by_name' => $auth->user()?->name,
            'counted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'difference' => $item->difference,
        ]);
    }

    // Completar conteo
    public function completeCount(InventoryCount $count)
    {
        if ($count->status !== 'in_progress') {
            return back()->with('error', 'El conteo no está en progreso');
        }

        $pendingItems = $count->items()->whereNull('counted_quantity')->count();

        if ($pendingItems > 0) {
            return back()->with('error', "Aún hay {$pendingItems} items sin contar");
        }

        $count->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Conteo completado. Puedes revisarlo ahora.');
    }

    // Reabrir conteo
    public function reopenCount(InventoryCount $count)
    {
        if (!in_array($count->status, ['completed', 'reviewed'])) {
            return back()->with('error', 'Solo se pueden reabrir conteos completados o revisados');
        }

        $count->update([
            'status' => 'in_progress',
            'completed_at' => null,
            'reviewed_at' => null,
            'reviewed_by' => null,
        ]);

        return back()->with('success', 'Conteo reabierto. Puedes continuar con la captura.');
    }

    // Revisar y aplicar ajustes
    public function reviewCount(InventoryCount $count)
    {
        if ($count->status !== 'completed') {
            return back()->with('error', 'El conteo debe estar completado');
        }

        DB::transaction(function () use ($count) {
            /** @var Guard $auth */
            $auth = auth();
            foreach ($count->items as $item) {
                if ($item->difference != 0) {
                    // Crear movimiento de ajuste
                    InventoryMovement::create([
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'type' => 'adjust',
                        'quantity' => $item->counted_quantity,
                        'reason' => "Ajuste por conteo: {$count->name}",
                        'reference_type' => InventoryCountItem::class,
                        'reference_id' => $item->id,
                        'created_by' => $auth->id(),
                    ]);

                    // Actualizar stock
                    if ($item->variant_id) {
                        $item->variant->update(['stock' => $item->counted_quantity]);
                    } else {
                        $item->product->update(['stock' => $item->counted_quantity]);
                    }
                }
            }

            $count->update([
                'status' => 'reviewed',
                'reviewed_by' => $auth->id(),
                'reviewed_at' => now(),
            ]);
        });

        return back()->with('success', 'Conteo revisado y ajustes aplicados al inventario');
    }

    // Exportar reporte de conteo
    public function exportCount(InventoryCount $count)
    {
        $items = $count->items()
            ->with(['product', 'variant'])
            ->get();

        $csv = "Producto,Variante,Stock Sistema,Contado,Diferencia,Valor Diferencia\n";

        foreach ($items as $item) {
            $productName = $item->product->name;
            $variantName = $item->variant ? $item->variant->name : 'N/A';
            $price = $item->variant ? $item->variant->price : $item->product->price;
            $valueDiff = ($item->difference ?? 0) * $price;

            $csv .= "\"{$productName}\",\"{$variantName}\",{$item->system_quantity},{$item->counted_quantity},{$item->difference}," . number_format($valueDiff, 2) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=conteo-' . $count->id . '.csv');
    }

    // ====== Captura pública ======
    public function publicCaptureForm(string $token)
    {
        $count = InventoryCount::where('public_token', $token)
            ->where('public_capture_enabled', true)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $items = $count->items()->with(['product', 'variant'])->paginate(50);

        return view('admin.inventory.counts.public-capture', compact('count', 'items', 'token'));
    }

    public function savePublicCapture(Request $request, string $token, InventoryCountItem $item)
    {
        $count = InventoryCount::where('public_token', $token)
            ->where('public_capture_enabled', true)
            ->where('status', 'in_progress')
            ->firstOrFail();

        if ($item->inventory_count_id !== $count->id) {
            abort(404);
        }

        $validated = $request->validate([
            'counted_quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string',
            'counter_name' => 'required|string|max:100',
        ]);

        /** @var Guard $auth */
        $auth = auth();
        $item->update([
            ...$validated,
            'counted_by' => $auth->id(),
            'counted_by_name' => $validated['counter_name'],
            'counted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'difference' => $item->difference,
        ]);
    }
}

