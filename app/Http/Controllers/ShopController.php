<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $currentCategory = null;
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();
            
        $parentCategories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->get();

        foreach ($parentCategories as $category) {
            $randomProduct = Product::where('category_id', $category->id)
                ->orWhere('subcategory_id', $category->id) // Include subcategory products if any
                ->where('is_active', true)
                ->whereHas('images')
                ->inRandomOrder()
                ->first();

            if ($randomProduct && $randomProduct->images->first()) {
                $category->random_image = $randomProduct->images->first()->url;
            }
        }

        $query = Product::where('is_active', true)
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
            });

        // Filter by category
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
                $currentCategory = $category;
            }
        }

        // Filter by subcategory
        if ($request->has('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(20);

        return view('shop.index', compact('categories', 'products', 'currentCategory', 'parentCategories'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        $products = Product::where('is_active', true)
            ->where('category_id', $category->id)
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
            ->latest();

        // Filter by subcategory
        if (request()->has('subcategory')) {
            $products->where('subcategory_id', request()->subcategory);
        }
        
        $products = $products->paginate(20);

        $subcategories = $category->children()->where('is_active', true)->withCount('products')->get();

        foreach ($subcategories as $subcategory) {
            $randomProduct = Product::where('subcategory_id', $subcategory->id)
                ->where('is_active', true)
                ->whereHas('images')
                ->inRandomOrder()
                ->first();

            if ($randomProduct && $randomProduct->images->first()) {
                $subcategory->random_image = $randomProduct->images->first()->url;
            }
        }
        
        $currentCategory = $category;

        return view('shop.index', compact('categories', 'products', 'currentCategory', 'subcategories'));
    }

    public function product($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['images', 'category', 'variants'])
            ->firstOrFail();

        // Related products from same category
        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images', 'variants'])
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
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('shop.product', compact('product', 'relatedProducts'));
    }
}
