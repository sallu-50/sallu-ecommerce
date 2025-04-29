<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('checkout');
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $cart = session()->get('cart', []);

        $cart[$product->id] = [
            'name' => $product->name,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.view')->with('success', 'Product added to cart.');
    }

    public function view()
    {
        $cart = session('cart', []);
        return view('frontend.cart.index', compact('cart'));
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'address' => 'required|string|max:255',
        ]);

        // Check if the address is received
        // dd($request->address);

        $user = auth()->user();

        DB::beginTransaction();

        try {
            // Order Creation
            $order = Order::create([
                'user_id' => $user->id,
                'total' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
                'address' => $request->address, // Save Address
                'status' => 'pending',
            ]);

            // Order Items
            foreach ($cart as $product_id => $item) {
                $order->items()->create([
                    'product_id' => $product_id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
