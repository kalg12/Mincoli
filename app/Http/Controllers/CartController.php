<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\RecommendationEngine;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $recommendationEngine;

    public function __construct(RecommendationEngine $recommendationEngine)
    {
        $this->recommendationEngine = $recommendationEngine;
    }
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = collect($cart)->map(function ($item) {
            $product = Product::with(['images', 'variants'])->find($item['product_id']);
            if (!$product) return null;

            $variant = null;
            $unitPrice = $product->sale_price ?? $product->price;
            $maxStock = $product->stock ?? $product->total_stock ?? 0;
            $imageUrl = $product->images->first()?->url ?? '/images/placeholder.jpg';

            if (!empty($item['variant_id'])) {
                $variant = $product->variants->firstWhere('id', $item['variant_id']);
                if ($variant) {
                    // Si la variante tiene precio, usarlo
                    $unitPrice = $variant->sale_price ?? ($variant->price ?? $unitPrice);
                    $maxStock = (int) $variant->stock;
                    $imageUrl = $variant->images()->first()?->url ?? $imageUrl;
                }
            }

            return (object)[
                'id' => $item['id'],
                'product' => $product,
                'variant' => $variant,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $item['quantity'],
                'max_stock' => $maxStock,
                'image_url' => $imageUrl
            ];
        })->filter();

        $subtotal = $items->sum('subtotal');
        $showIva = SiteSetting::get('store', 'show_iva', true);
        $iva = $showIva ? ($subtotal * 0.16) : 0;
        $total = $subtotal + $iva;

        // Obtener recomendaciones inteligentes
        $recommendations = $this->recommendationEngine->getRecommendations($cart, 6);

        return view('cart', [
            'items' => $items,
            'cart' => (object)[
                'subtotal' => $subtotal,
                'total_iva' => $iva,
                'total' => $total,
                'show_iva' => $showIva
            ],
            'recommendations' => $recommendations
        ]);
    }

    public function add(Request $request)
    {
        Log::info('CartController@add called', [
            'data' => $request->all(),
            'method' => $request->method(),
            'expects_json' => $request->expectsJson()
        ]);

        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'variant_id' => 'nullable|integer'
            ]);

            $product = Product::with('variants')->findOrFail($request->product_id);

            Log::info('Product found', [
                'product_id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock,
                'total_stock' => $product->total_stock
            ]);

            // Verificar stock disponible (si se selecciona variante, usar su stock)
            $availableStock = $product->total_stock;
            $selectedVariantId = $request->input('variant_id');

            // Si el producto tiene variantes, exigir selección de variante
            if ($product->variants->count() > 0 && empty($selectedVariantId)) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Por favor selecciona una variante'
                    ], 422);
                }
                return back()->with('error', 'Por favor selecciona una variante');
            }
            if ($selectedVariantId) {
                $variant = $product->variants->firstWhere('id', (int)$selectedVariantId);
                if (!$variant) {
                    if (request()->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'La variante seleccionada no pertenece al producto'
                        ], 422);
                    }
                    return back()->with('error', 'La variante seleccionada no pertenece al producto');
                }
                $availableStock = $variant->stock;
            }
            if ($availableStock < $request->quantity) {
                Log::warning('Insufficient stock', [
                    'available' => $availableStock,
                    'requested' => $request->quantity
                ]);

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay suficiente stock disponible'
                    ], 422);
                }
                return back()->with('error', 'No hay suficiente stock disponible');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error in add method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        $cart = session()->get('cart', []);

        // Verificar si el producto (y variante si aplica) ya existe en el carrito
        $existingKey = null;
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $request->product_id && (($item['variant_id'] ?? null) == ($selectedVariantId ?? null))) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey !== null) {
            // Actualizar cantidad
            $newQuantity = $cart[$existingKey]['quantity'] + $request->quantity;

            // Verificar stock para la nueva cantidad
            if ($availableStock < $newQuantity) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay suficiente stock disponible'
                    ], 422);
                }
                return back()->with('error', 'No hay suficiente stock disponible');
            }

            $cart[$existingKey]['quantity'] = $newQuantity;
        } else {
            // Agregar nuevo item
            $cart[] = [
                'id' => uniqid(),
                'product_id' => $request->product_id,
                'variant_id' => $selectedVariantId,
                'quantity' => $request->quantity
            ];
        }

        session()->put('cart', $cart);

        Log::info('Product added to cart', [
            'cart_count' => count($cart),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto agregado al carrito',
                'cart_count' => count($cart)
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        $updated = false;
        $message = 'Carrito actualizado';

        foreach ($cart as $key => $item) {
            if ($item['id'] == $id) {
                // Obtener producto y variante para validar stock
                $product = Product::with('variants')->find($item['product_id']);
                if (!$product) break;

                $availableStock = $product->total_stock;
                if (!empty($item['variant_id'])) {
                    $variant = $product->variants->firstWhere('id', $item['variant_id']);
                    if ($variant) {
                        $availableStock = (int) $variant->stock;
                    }
                }

                $newQty = (int) $request->quantity;
                if ($newQty > $availableStock) {
                    $newQty = max(1, (int) $availableStock);
                    $message = 'Cantidad ajustada al stock disponible';
                }

                $cart[$key]['quantity'] = $newQty;
                $updated = true;
                break;
            }
        }

        if ($updated) {
            session()->put('cart', $cart);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        $cart = array_filter($cart, function($item) use ($id) {
            return $item['id'] != $id;
        });

        session()->put('cart', array_values($cart));

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Producto eliminado']);
        }

        return back()->with('success', 'Producto eliminado del carrito');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Carrito vaciado');
    }

    public function getCartData()
    {
        $cart = session()->get('cart', []);
        $items = collect($cart)->map(function ($item) {
            // Forzar refrescar datos del producto para evitar cache
            $product = Product::with(['images', 'variants'])->find($item['product_id']);
            if (!$product) return null;

            $variant = null;
            $unitPrice = $product->sale_price ?? $product->price;
            $maxStock = $product->stock ?? $product->total_stock ?? 0;
            $imageUrl = $product->images->first()?->url ?? '/images/placeholder.jpg';

            if (!empty($item['variant_id'])) {
                $variant = $product->variants->firstWhere('id', $item['variant_id']);
                if ($variant) {
                    $unitPrice = $variant->sale_price ?? ($variant->price ?? $unitPrice);
                    $maxStock = (int) $variant->stock;
                    $imageUrl = $variant->images()->first()?->url ?? $imageUrl;
                }
            }

            $quantity = $item['quantity'];

            return [
                'id' => $item['id'],
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'slug' => $product->slug,
                    'image' => $imageUrl
                ],
                'variant' => $variant ? [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'sku' => $variant->sku,
                    'size' => $variant->size,
                    'color' => $variant->color
                ] : null,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $quantity,
                'max_stock' => $maxStock
            ];
        })->filter();

        $subtotal = $items->sum('subtotal');
        $showIva = SiteSetting::get('store', 'show_iva', true);
        $iva = $showIva ? ($subtotal * 0.16) : 0;
        $total = $subtotal + $iva;

        // Obtener recomendaciones inteligentes
        $recommendations = $this->recommendationEngine->getRecommendations($cart, 4);

        $recommendationsData = $recommendations->map(function ($product) {
            // Asegurar relaciones para componer información de variantes
            $product->loadMissing(['images', 'variants']);
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => $product->sale_price ?? $product->price,
                'original_price' => $product->price,
                'has_discount' => $product->sale_price && $product->sale_price < $product->price,
                'image' => $product->images->first()?->url ?? '/images/placeholder.jpg',
                'stock' => $product->stock,
                'has_variants' => $product->variants->count() > 0,
                'variants' => $product->variants->map(function ($v) use ($product) {
                    $productBaseImage = $product->images->first()?->url ?? '/images/placeholder.jpg';
                    return [
                        'id' => $v->id,
                        'name' => $v->name,
                        'size' => $v->size,
                        'color' => $v->color,
                        'price' => $v->sale_price ?? ($v->price ?? ($product->sale_price ?? $product->price)),
                        'sale_price' => $v->sale_price,
                        'stock' => $v->stock,
                        'sku' => $v->sku,
                        'image' => $v->images()->first()?->url ?? $productBaseImage,
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'items' => $items->values(),
            'recommendations' => $recommendationsData,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'show_iva' => $showIva
        ]);
    }
}
