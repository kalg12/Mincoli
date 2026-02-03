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
            ->with(['children' => function($q) {
                $q->where('is_active', true)->withCount('subcategoryProducts');
            }])
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

        $parentCategories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->where('is_active', true)->withCount('subcategoryProducts');
            }])
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

        return view('shop.index', compact('categories', 'products', 'currentCategory', 'subcategories', 'parentCategories'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('shop');
        }

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        $parentCategories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->where('is_active', true)->withCount('subcategoryProducts');
            }])
            ->withCount('products')
            ->get();

        $products = Product::where('is_active', true)
            ->with(['images', 'category', 'variants'])
            ->where(function ($q) use ($query) {
                // Búsqueda exacta primero (SKU y Barcode)
                $q->where('sku', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%")
                  // Búsqueda en nombre y descripción
                  ->orWhere('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  // Búsqueda en variantes
                  ->orWhereHas('variants', function ($variantQuery) use ($query) {
                      $variantQuery->where('name', 'like', "%{$query}%")
                                  ->orWhere('sku', 'like', "%{$query}%")
                                  ->orWhere('barcode', 'like', "%{$query}%")
                                  ->orWhere('size', 'like', "%{$query}%")
                                  ->orWhere('color', 'like', "%{$query}%");
                  });
            })
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
            ->orderByRaw("
                CASE 
                    WHEN sku LIKE ? THEN 1
                    WHEN barcode LIKE ? THEN 1
                    WHEN name LIKE ? THEN 2
                    ELSE 3
                END
            ", ["%{$query}%", "%{$query}%", "%{$query}%"]);

        // Aplicar filtros adicionales si existen
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $products->where('category_id', $category->id);
            }
        }

        if ($request->has('min_price')) {
            $products->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $products->where('price', '<=', $request->max_price);
        }

        // Sorting
        switch ($request->get('sort', 'relevance')) {
            case 'price_asc':
                $products->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $products->orderBy('price', 'desc');
                break;
            case 'name':
                $products->orderBy('name', 'asc');
                break;
            case 'newest':
                $products->latest();
                break;
            default:
                // Mantener orden por relevancia
                break;
        }

        $products = $products->paginate(20);

        return view('shop.search', compact('products', 'categories', 'query', 'parentCategories'));
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
