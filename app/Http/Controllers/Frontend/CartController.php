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

        // If already added, increase quantity
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ];
        }

        session()->put('cart', $cart);

        // Calculate total quantity of items in cart
        $totalQuantity = 0;
        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
        }

        return response()->json([
            'status' => 'success',
            'cart_count' => $totalQuantity,
            'cart_items' => $cart,
        ]);
    }

    public function view()
    {
        $cart = session('cart', []);
        $totals = $this->getCartTotals($cart);

        return view('frontend.cart.index', [
            'cart' => $cart,
            'total' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'newTotal' => $totals['finalTotal'],
        ]);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error_message', 'Your cart is empty.');
        }

        $request->validate([
            'address' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $totals = $this->getCartTotals($cart);

        DB::beginTransaction();

        try {
            // Order Creation
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $totals['finalTotal'], // Save the final discounted total
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
            session()->forget(['cart', 'coupon']);

            return redirect()->route('orders.confirmation', $order->id)->with('success_message', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error_message', 'Something went wrong. Please try again.');
        }
    }

    private function getCartTotals(array $cart): array
    {
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = 0;

        if (session()->has('coupon')) {
            $coupon = session('coupon');
            if ($coupon['type'] === 'fixed') {
                $discount = $coupon['value'];
            } elseif ($coupon['type'] === 'percent') {
                $discount = ($subtotal * $coupon['value']) / 100;
            }
        }

        $finalTotal = $subtotal - $discount;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'finalTotal' => $finalTotal > 0 ? $finalTotal : 0,
        ];
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon'); // Also clear coupon if present
        return back()->with('success_message', 'Your cart has been cleared.');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $quantity = $request->quantity;

        if ($quantity === 0) {
            unset($cart[$productId]);
        } else {
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
            } else {
                // This case should ideally not happen if update is only for existing items
                // But if it does, we can add the product
                $product = Product::find($productId);
                if ($product) {
                    $cart[$productId] = [
                        'name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ];
                }
            }
        }

        session()->put('cart', $cart);

        // Recalculate totals and cart count
        $totals = $this->getCartTotals($cart);
        $totalQuantity = 0;
        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
        }

        return response()->json([
            'status' => 'success',
            'cart_items' => $cart,
            'cart_count' => $totalQuantity,
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'newTotal' => $totals['finalTotal'],
        ]);
    }
}
