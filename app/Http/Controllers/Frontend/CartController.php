<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Session;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $cart[$product->id] = [
            "name" => $product->name,
            "quantity" => $request->quantity,
            "price" => $product->price,
        ];

        session()->put('cart', $cart);
        return redirect()->route('cart.view')->with('success', 'Product added to cart');
    }

    public function view()
    {
        $cart = session('cart', []);
        return view('frontend.cart.index', compact('cart'));
    }

    public function __construct()
    {
        $this->middleware('auth')->only(['checkout']);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $user = auth()->user();

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id, // <-- ekhane user_id assign kora hocche
                'total' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
                'status' => 'pending',
            ]);

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
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
