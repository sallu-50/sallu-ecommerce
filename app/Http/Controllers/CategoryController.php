<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Show the products for a specific category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Fetch the category by ID
        $category = Category::findOrFail($id);

        // Fetch the products for this category
        $products = Product::where('category_id', $id)->get();

        // Return a view and pass the category and products to it
        return view('frontend.category.show', compact('category', 'products'));
    }
}
