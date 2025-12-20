<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'variants'])
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:published,draft,out_of_stock',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $validated['status'] === 'published';
        $validated['cost'] = $validated['price'] * 0.6;

        Product::create($validated);

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function edit($id)
    {
        $product = Product::with([
            'variants',
            'inventoryMovements' => function ($query) {
                $query->latest('created_at')->limit(20);
            },
            'inventoryMovements.variant'
        ])->findOrFail($id);
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:published,draft,out_of_stock',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $validated['status'] === 'published';

        $product->update($validated);

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Producto eliminado correctamente');
    }

    public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();

        return back()->with('success', $product->is_featured ? 'Producto marcado como destacado' : 'Producto desmarcado como destacado');
    }

    public function storeVariant(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'sku' => 'required|string|max:100|unique:product_variants,sku',
            'barcode' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $validated['product_id'] = $productId;

        $product->variants()->create($validated);

        return redirect()
            ->route('dashboard.products.edit', $productId)
            ->with('success', 'Variante creada correctamente');
    }

    public function updateVariant(Request $request, $productId, $variantId)
    {
        Product::findOrFail($productId);
        $variant = \App\Models\ProductVariant::findOrFail($variantId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'sku' => 'required|string|max:100|unique:product_variants,sku,' . $variantId,
            'barcode' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $variant->update($validated);

        return redirect()
            ->route('dashboard.products.edit', $productId)
            ->with('success', 'Variante actualizada correctamente');
    }

    public function destroyVariant($productId, $variantId)
    {
        Product::findOrFail($productId);
        \App\Models\ProductVariant::findOrFail($variantId)->delete();

        return redirect()
            ->route('dashboard.products.edit', $productId)
            ->with('success', 'Variante eliminada correctamente');
    }

    public function adjustStock(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $validated = $request->validate([
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:0',
            'type' => 'required|in:entrada,salida',
            'reason' => 'required|string|max:255',
        ]);

        $variantId = $validated['variant_id'];
        $quantity = $validated['quantity'];
        $type = $validated['type'];

        // Actualizar stock de la variante
        if ($variantId) {
            $variant = \App\Models\ProductVariant::findOrFail($variantId);
            if ($type === 'entrada') {
                $variant->increment('stock', $quantity);
            } else {
                $variant->decrement('stock', $quantity);
            }
        } else {
            // Si no hay variante, actualizar el stock del producto directamente
            if ($type === 'entrada') {
                $product->increment('stock', $quantity);
            } else {
                $product->decrement('stock', $quantity);
            }
        }

        // Registrar el movimiento de inventario
        $product->inventoryMovements()->create([
            'variant_id' => $variantId,
            'type' => $type,
            'quantity' => $quantity,
            'reason' => $validated['reason'],
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('dashboard.products.edit', $productId)
            ->with('success', 'Stock ajustado correctamente');
    }

    public function printLabels(Request $request)
    {
        $productIds = $request->input('products', []);
        $products = Product::with('category')->whereIn('id', $productIds)->get();

        return view('admin.products.print-labels', compact('products'));
    }
}
