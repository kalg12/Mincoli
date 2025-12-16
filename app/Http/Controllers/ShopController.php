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

        $query = Product::where('is_active', true)
            ->with(['images', 'category', 'variants']);

        // Filter by category
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
                $currentCategory = $category;
            }
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

        return view('shop.index', compact('categories', 'products', 'currentCategory'));
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
            ->latest()
            ->paginate(20);

        $currentCategory = $category;

        return view('shop.index', compact('categories', 'products', 'currentCategory'));
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
            ->with(['images'])
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('shop.product', compact('product', 'relatedProducts'));
    }
}
