<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // Featured products
        $featuredProducts = Product::where('is_featured', true)->take(8)->get();

        // Categories with products
        $categories = Category::with('products')->get();

        // Best selling products
        $bestSellingProducts = Product::orderBy('sold', 'desc')->take(8)->get();

        // New arrivals (latest products)
        $newArrivals = Product::latest()->take(8)->get();

        return view('frontend.products.index', compact('featuredProducts', 'categories', 'bestSellingProducts', 'newArrivals'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('frontend.products.show', compact('product'));
    }
}
