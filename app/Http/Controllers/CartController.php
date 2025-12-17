<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\RecommendationEngine;

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

            return (object)[
                'id' => $item['id'],
                'product' => $product,
                'quantity' => $item['quantity'],
                'unit_price' => $product->sale_price ?? $product->price,
                'subtotal' => ($product->sale_price ?? $product->price) * $item['quantity']
            ];
        })->filter();

        $subtotal = $items->sum('subtotal');
        $iva = $subtotal * 0.16;
        $total = $subtotal + $iva;

        // Obtener recomendaciones inteligentes
        $recommendations = $this->recommendationEngine->getRecommendations($cart, 6);

        return view('cart', [
            'items' => $items,
            'cart' => (object)[
                'subtotal' => $subtotal,
                'total_iva' => $iva,
                'total' => $total
            ],
            'recommendations' => $recommendations
        ]);
    }

    public function add(Request $request)
    {
        \Log::info('CartController@add called', [
            'data' => $request->all(),
            'method' => $request->method(),
            'expects_json' => $request->expectsJson()
        ]);

        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::with('variants')->findOrFail($request->product_id);

            \Log::info('Product found', [
                'product_id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock,
                'total_stock' => $product->total_stock
            ]);

            // Verificar stock disponible
            $availableStock = $product->total_stock;
            if ($availableStock < $request->quantity) {
                \Log::warning('Insufficient stock', [
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
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error in add method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        $cart = session()->get('cart', []);

        // Verificar si el producto ya existe en el carrito
        $existingKey = null;
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $request->product_id) {
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
                'quantity' => $request->quantity
            ];
        }

        session()->put('cart', $cart);

        \Log::info('Product added to cart', [
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

        foreach ($cart as $key => $item) {
            if ($item['id'] == $id) {
                $cart[$key]['quantity'] = $request->quantity;
                break;
            }
        }

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Carrito actualizado']);
        }

        return back()->with('success', 'Carrito actualizado');
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

            $unitPrice = $product->sale_price ?? $product->price;
            $quantity = $item['quantity'];

            return [
                'id' => $item['id'],
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'slug' => $product->slug,
                    'image' => $product->images->first()?->url ?? '/images/placeholder.jpg'
                ],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $quantity
            ];
        })->filter();

        $subtotal = $items->sum('subtotal');
        $iva = $subtotal * 0.16;
        $total = $subtotal + $iva;

        // Obtener recomendaciones inteligentes
        $recommendations = $this->recommendationEngine->getRecommendations($cart, 4);

        $recommendationsData = $recommendations->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => $product->sale_price ?? $product->price,
                'original_price' => $product->price,
                'has_discount' => $product->sale_price && $product->sale_price < $product->price,
                'image' => $product->images->first()?->url ?? '/images/placeholder.jpg',
                'stock' => $product->stock
            ];
        });

        return response()->json([
            'items' => $items->values(),
            'recommendations' => $recommendationsData,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total
        ]);
    }
}
