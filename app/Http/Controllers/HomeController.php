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
            ->withCount('products')
            ->get();

        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['images', 'category'])
            ->take(10)
            ->get();

        $banners = Banner::active()->orderBy('position')->get();

        return view('home', compact('categories', 'featuredProducts', 'banners'));
    }
}
