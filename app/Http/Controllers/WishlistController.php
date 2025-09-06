<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistProducts = auth()->user()->wishlistProducts()->get();
        return view('frontend.wishlist.index', compact('wishlistProducts'));
    }

    public function store(Product $product)
    {
        $wishlist = auth()->user()->wishlistProducts();

        if (!$wishlist->where('product_id', $product->id)->exists()) {
            $wishlist->attach($product->id);
            return back()->with('success_message', 'Product added to wishlist!');
        }

        return back()->with('success_message', 'Product is already in your wishlist.');
    }

    public function destroy(Product $product)
    {
        auth()->user()->wishlistProducts()->detach($product->id);
        return back()->with('success_message', 'Product removed from wishlist.');
    }
}
