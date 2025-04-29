<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerOrderController extends Controller
{
    public function index()
    {
        // $product = Product::findOrFail($request->product_id);
        // $cart = session()->get('cart', []);

        // $cart[$product->id] = [
        //     "name" => $product->name,
        //     "quantity" => $request->quantity,
        //     "price" => $product->price,
        // $orders = Order::with('items.product')
        //     ->where('user_id', auth()->id())
        //     ->latest()
        //     ->get();
        // $orders = auth()->user()->orders()->with('items.product')->latest()->get();


        $orders = Order::with('items.product', 'user')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('frontend.orders.index', compact('orders'));




        // $orders = auth()->user()->orders()->with('items.product')->latest()->get();
        // $orders = Order::latest()->get();
        // return view('frontend.orders.index', compact('orders'));
    }

    public function downloadInvoice($id)
    {
        $order = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $pdf = Pdf::loadView('frontend.orders.invoice', compact('order'));

        return $pdf->download('invoice_order_' . $order->id . '.pdf');
    }
}
