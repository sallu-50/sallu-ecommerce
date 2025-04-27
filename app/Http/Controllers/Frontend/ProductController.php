<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(12);
        return view('frontend.products.index', compact('products'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('frontend.products.show', compact('product'));
    }
}
