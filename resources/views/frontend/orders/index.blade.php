@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">My Orders</h1>

        @forelse ($orders as $order)
            <div class="border rounded-xl p-4 mb-4 shadow">
                <div class="flex justify-between">
                    <div>
                        <h2 class="text-xl font-bold mb-2">Order #{{ $order->id }}</h2>
                        <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                        <p><strong>Shipping Address:</strong> {{ $order->address }}</p>
                    </div>
                    <div class="text-green-600 font-semibold">৳{{ number_format($order->total, 2) }}</div>
                </div>


                <div class="mt-3 space-y-1 text-sm text-gray-700">
                    <h1 class="mt-3 font-semibold">Products:</h1>
                    @foreach ($order->items as $item)
                        <div class="flex justify-between">
                            <div>{{ $item->product->name }} (x{{ $item->quantity }})</div>

                            <div>৳{{ $item->product->price * $item->quantity }}</div>
                        </div>
                    @endforeach
                </div>
                <h1 class="mt-3 font-semibold">Description:</h1>
                <div> {{ $item->product->description }}</div>
                <p class="font-semibold">Status: {{ $order->status }}</p>



                <div class="mt-2">
                    <a href="{{ route('orders.invoice', $order->id) }}" class="text-sm text-blue-600 hover:underline"
                        target="_blank">
                        Download Invoice
                    </a>
                </div>
            </div>
        @empty
            <p>You haven’t placed any orders yet.</p>
        @endforelse


    </div>
@endsection
