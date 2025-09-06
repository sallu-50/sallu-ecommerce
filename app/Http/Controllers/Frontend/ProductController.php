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


    public function filter(Request $request)
    {
        $categories = Category::all();

        $productsQuery = Product::query()
            ->when($request->category_id, fn($query) => $query->where('category_id', $request->category_id))
            ->when($request->min_price, fn($query) => $query->where('price', '>=', $request->min_price))
            ->when($request->max_price, fn($query) => $query->where('price', '<=', $request->max_price));

        // Handle sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $productsQuery->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $productsQuery->orderBy('price', 'desc');
        } else {
            $productsQuery->latest();
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        if ($request->ajax()) {
            return view('frontend.filter.index', compact('products'))->render();
        }

        return view('frontend.products.shop', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('name', 'LIKE', "%{$query}%")
                             ->orWhere('description', 'LIKE', "%{$query}%")
                             ->paginate(12);

        return view('frontend.search.results', compact('products', 'query'));
    }
}
