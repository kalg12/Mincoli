<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->get();

        foreach ($categories as $category) {
            $randomProduct = Product::where(function($query) use ($category) {
                    $query->where('category_id', $category->id)
                          ->orWhere('subcategory_id', $category->id);
                })
                ->where('is_active', true)
                ->whereHas('images')
                ->inRandomOrder()
                ->first();

            if ($randomProduct && $randomProduct->images->first()) {
                $category->random_image = $randomProduct->images->first()->url;
            }
        }

        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['images', 'category', 'variants'])
            ->where(function($q) {
                // Products with variants: at least one variant must have stock
                $q->whereHas('variants', function($variantQuery) {
                    $variantQuery->where('stock', '>', 0);
                })
                // Products without variants: product itself must have stock
                ->orWhere(function($noVariantQuery) {
                    $noVariantQuery->whereDoesntHave('variants')
                        ->where('stock', '>', 0);
                });
            })
            ->take(10)
            ->get();

        $banners = Banner::active()->orderBy('position')->get();

        return view('home', compact('categories', 'featuredProducts', 'banners'));
    }
}
