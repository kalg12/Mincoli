<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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

    public function trash()
    {
        $products = Product::onlyTrashed()
            ->with(['category', 'variants'])
            ->latest('deleted_at')
            ->paginate(15);

        return view('admin.products.trash', compact('products'));
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
            'cost' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:published,draft,out_of_stock',
            'iva_rate' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|max:5120',
        ]);

        $payload = $validated;
        $payload['slug'] = $this->generateUniqueSlug($validated['name']);
        $payload['is_active'] = $validated['status'] === 'published';
        $payload['is_featured'] = $request->boolean('is_featured');
        $payload['cost'] = $validated['cost'] ?? ($validated['price'] * 0.6);

        $product = Product::create($payload);

        $position = 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/product-images');
            ProductImage::create([
                'product_id' => $product->id,
                'url' => Storage::url($path),
                'position' => $position++,
            ]);
        }

        if ($request->filled('image_url')) {
            $storedUrl = $this->downloadAndStoreDriveImage($request->input('image_url'));
            if ($storedUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $storedUrl,
                    'position' => $position,
                ]);
            }
        }

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function edit($id)
    {
        $product = Product::with([
            'variants',
            'images',
            'inventoryMovements' => fn($query) => $query->latest('created_at')->limit(20),
            'inventoryMovements.variant',
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
            'cost' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'status' => 'required|in:published,draft,out_of_stock',
            'iva_rate' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|max:5120',
        ]);

        $payload = $validated;
        $payload['is_active'] = $validated['status'] === 'published';
        $payload['is_featured'] = $request->boolean('is_featured');

        if ($product->name !== $validated['name']) {
            $payload['slug'] = $this->generateUniqueSlug($validated['name'], $product->id);
        }

        $product->update($payload);

        $position = ($product->images()->max('position') ?? -1) + 1;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/product-images');
            ProductImage::create([
                'product_id' => $product->id,
                'url' => Storage::url($path),
                'position' => $position++,
            ]);
        }

        if ($request->filled('image_url')) {
            $storedUrl = $this->downloadAndStoreDriveImage($request->input('image_url'));
            if ($storedUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $storedUrl,
                    'position' => $position,
                ]);
            }
        }

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        $product = Product::with('variants')->findOrFail($id);

        DB::transaction(function () use ($product) {
            if ($product->variants->count() > 0) {
                foreach ($product->variants as $variant) {
                    if ($variant->stock > 0) {
                        $product->inventoryMovements()->create([
                            'variant_id' => $variant->id,
                            'type' => 'out',
                            'quantity' => $variant->stock,
                            'reason' => 'Producto eliminado',
                            'created_by' => Auth::id(),
                        ]);
                        $variant->update(['stock' => 0]);
                    }
                }
            } elseif ($product->stock > 0) {
                $product->inventoryMovements()->create([
                    'variant_id' => null,
                    'type' => 'out',
                    'quantity' => $product->stock,
                    'reason' => 'Producto eliminado',
                    'created_by' => Auth::id(),
                ]);
                $product->update(['stock' => 0]);
            }

            $product->images()->delete();
            $product->variants()->delete();
            $product->delete();
        });

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Producto eliminado correctamente');
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($product) {
            $product->restore();
            $product->images()->withTrashed()->restore();
            $product->variants()->withTrashed()->restore();
        });

        return redirect()
            ->route('dashboard.products.trash')
            ->with('success', 'Producto restaurado correctamente');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()
            ->with(['images' => fn($q) => $q->withTrashed(), 'variants' => fn($q) => $q->withTrashed()])
            ->findOrFail($id);

        DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                $original = $image->getRawOriginal('url') ?? $image->url;
                if ($original && str_starts_with($original, '/storage/')) {
                    $path = str_replace('/storage/', 'public/', $original);
                    Storage::delete($path);
                }
            }

            $product->images()->withTrashed()->forceDelete();
            $product->variants()->withTrashed()->forceDelete();
            $product->forceDelete();
        });

        return redirect()
            ->route('dashboard.products.trash')
            ->with('success', 'Producto eliminado permanentemente');
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
            'image' => 'nullable|image|max:5120',
        ]);

        $variant = $product->variants()->create($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/product-images');
            ProductImage::create([
                'product_id' => $productId,
                'variant_id' => $variant->id,
                'url' => Storage::url($path),
                'position' => 0,
            ]);
        }

        return redirect()
            ->route('dashboard.products.edit', $productId)
            ->with('success', 'Variante creada correctamente');
    }

    public function updateVariant(Request $request, $productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::where('product_id', $product->id)->findOrFail($variantId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'sku' => 'required|string|max:100|unique:product_variants,sku,' . $variantId,
            'barcode' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:5120',
        ]);

        $variant->update($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/product-images');
            ProductImage::create([
                'product_id' => $productId,
                'variant_id' => $variant->id,
                'url' => Storage::url($path),
                'position' => 0,
            ]);
        }

        return back()->with('success', 'Variante actualizada correctamente');
    }

    public function destroyVariant($productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::where('product_id', $product->id)->findOrFail($variantId);

        DB::transaction(function () use ($product, $variant) {
            if ($variant->stock > 0) {
                $product->inventoryMovements()->create([
                    'variant_id' => $variant->id,
                    'type' => 'out',
                    'quantity' => $variant->stock,
                    'reason' => 'Variante eliminada',
                    'created_by' => Auth::id(),
                ]);
                $variant->update(['stock' => 0]);
            }

            $variant->images()->delete();
            $variant->delete();
        });

        return back()->with('success', 'Variante eliminada correctamente');
    }

    public function destroyImage($id, $imageId)
    {
        $image = ProductImage::where('product_id', $id)->findOrFail($imageId);

        $originalUrl = $image->getRawOriginal('url') ?? $image->url;
        if ($originalUrl && str_starts_with($originalUrl, '/storage/')) {
            $path = str_replace('/storage/', 'public/', $originalUrl);
            Storage::delete($path);
        }

        $image->delete();

        return back()->with('success', 'Imagen eliminada correctamente');
    }

    public function adjustStock(Request $request, $productId)
    {
        $product = Product::with('variants')->findOrFail($productId);

        $validated = $request->validate([
            'variant_id' => 'nullable|exists:product_variants,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:100',
        ]);

        DB::transaction(function () use ($product, $validated) {
            $variant = null;
            if (!empty($validated['variant_id'])) {
                $variant = ProductVariant::where('product_id', $product->id)->findOrFail($validated['variant_id']);
            }

            $isIn = $validated['type'] === 'in';
            $qty = $validated['quantity'];

            if ($variant) {
                $newStock = $isIn
                    ? $variant->stock + $qty
                    : max(0, $variant->stock - $qty);
                $variant->update(['stock' => $newStock]);
            } else {
                $newStock = $isIn
                    ? $product->stock + $qty
                    : max(0, $product->stock - $qty);
                $product->update(['stock' => $newStock]);
            }

            $product->inventoryMovements()->create([
                'variant_id' => $variant?->id,
                'type' => $validated['type'],
                'quantity' => $qty,
                'reason' => $validated['reason'],
                'created_by' => Auth::id(),
            ]);
        });

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

    private function downloadAndStoreDriveImage(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $direct = ProductImage::resolveDriveUrl($url);
        if (!$direct) {
            return null;
        }

        $response = Http::timeout(10)->get($direct);
        if (!$response->ok()) {
            return null;
        }

        $contentType = $response->header('Content-Type');
        $ext = 'jpg';
        if ($contentType === 'image/png') {
            $ext = 'png';
        } elseif ($contentType === 'image/webp') {
            $ext = 'webp';
        } elseif ($contentType === 'image/jpeg' || $contentType === 'image/jpg') {
            $ext = 'jpg';
        }

        $filename = 'product-images/' . uniqid('drive_', true) . '.' . $ext;
        Storage::put('public/' . $filename, $response->body());

        return Storage::url('public/' . $filename);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'producto';
        $slug = $base;
        $i = 1;

        while (
            Product::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
