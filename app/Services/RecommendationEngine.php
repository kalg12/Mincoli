<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class RecommendationEngine
{
    /**
     * Generar recomendaciones basadas en los items del carrito
     *
     * Estrategia:
     * 1. Productos de las mismas categorías (peso 40%)
     * 2. Productos en rango de precio similar (peso 30%)
     * 3. Productos populares/mejor valorados (peso 20%)
     * 4. Productos complementarios por tags (peso 10%)
     */
    public function getRecommendations(array $cartItems, int $limit = 4): Collection
    {
        if (empty($cartItems)) {
            return $this->getFallbackRecommendations($limit);
        }

        $productIds = collect($cartItems)->pluck('product_id')->toArray();
        $products = Product::with('category')->whereIn('id', $productIds)->get();

        // Obtener categorías del carrito
        $categoryIds = $products->filter(function ($product) {
            return $product->category !== null;
        })->pluck('category.id')->unique()->toArray();

        // Calcular rango de precios del carrito
        $prices = $products->map(function ($product) {
            return $product->sale_price ?? $product->price;
        });
        $avgPrice = $prices->avg();
        $minPrice = $avgPrice * 0.5; // 50% del promedio
        $maxPrice = $avgPrice * 1.5; // 150% del promedio

        // Query base: productos que no están en el carrito
        $recommendations = Product::with(['images', 'category'])
            ->whereNotIn('id', $productIds)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();

        // Calcular score para cada producto
        $scoredProducts = $recommendations->map(function ($product) use ($categoryIds, $minPrice, $maxPrice) {
            $score = 0;
            $price = $product->sale_price ?? $product->price;

            // 1. Misma categoría (40 puntos)
            if ($product->category && in_array($product->category->id, $categoryIds)) {
                $score += 40;
            }

            // 2. Rango de precio similar (30 puntos)
            if ($price >= $minPrice && $price <= $maxPrice) {
                $score += 30;
            } elseif ($price < $minPrice) {
                // Productos más baratos obtienen menos puntos pero siguen siendo relevantes
                $score += 15;
            }

            // 3. Productos populares/destacados (20 puntos)
            if ($product->is_featured) {
                $score += 20;
            }

            // 4. Descuentos activos (bonus 10 puntos)
            if ($product->sale_price && $product->sale_price < $product->price) {
                $score += 10;
            }

            // 5. Stock alto = disponibilidad (bonus 5 puntos)
            if ($product->stock > 10) {
                $score += 5;
            }

            $product->recommendation_score = $score;
            return $product;
        });

        // Ordenar por score y tomar los mejores
        return $scoredProducts
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
    }

    /**
     * Recomendaciones cuando el carrito está vacío
     */
    private function getFallbackRecommendations(int $limit): Collection
    {
        return Product::with(['images', 'category'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function ($query) {
                $query->where('is_featured', true)
                    ->orWhereNotNull('sale_price');
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Recomendaciones basadas en un producto específico
     */
    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        $categoryId = $product->category_id;
        $price = $product->sale_price ?? $product->price;
        $minPrice = $price * 0.6;
        $maxPrice = $price * 1.4;

        return Product::with(['images', 'category'])
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function ($query) use ($categoryId, $minPrice, $maxPrice) {
                $query->where('category_id', $categoryId)
                    ->orWhereBetween('price', [$minPrice, $maxPrice]);
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
