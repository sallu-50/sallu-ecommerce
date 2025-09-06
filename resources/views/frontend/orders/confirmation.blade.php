@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 text-center">
        <h1 class="text-3xl font-bold text-green-600 mb-4">🎉 Order Confirmed!</h1>
        <p class="text-lg text-gray-700 mb-6">Thank you for your purchase. Your order has been successfully placed.</p>

        @if (isset($order))
            <div class="bg-white p-6 rounded-lg shadow-md inline-block text-left">
                <h2 class="text-xl font-semibold mb-4">Order Details (#{{ $order->id }})</h2>
                <p class="mb-2"><strong>Total:</strong> ৳{{ number_format($order->total, 2) }}</p>
                <p class="mb-2"><strong>Shipping Address:</strong> {{ $order->address }}</p>
                <p class="mb-4"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

                <h3 class="font-semibold mb-2">Items:</h3>
                <ul class="list-disc list-inside mb-4">
                    @foreach ($order->items as $item)
                        <li>{{ $item->product->name }} (x{{ $item->quantity }}) - ৳{{ number_format($item->price * $item->quantity, 2) }}</li>
                    @endforeach
                </ul>

                <div class="mt-4 space-x-4">
                    <a href="{{ route('orders.invoice', $order->id) }}" class="text-blue-600 hover:underline inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zM16.875 19.5h.008v.008h-.008v-.008zM18 15.75h.008v.008H18v-.008z" />
                        </svg>
                        Download Invoice
                    </a>
                    <a href="{{ route('orders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 10.5h12M8.25 14.25h12M8.25 18h12M3 6.75H3.75M3 10.5H3.75M3 14.25H3.75M3 18H3.75" />
                        </svg>
                        View All Orders
                    </a>
                </div>
            </div>
        @else
            <p class="text-red-500">Order details could not be loaded.</p>
        @endif

        <div class="mt-8">
            <a href="{{ route('filter.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 text-lg">Continue Shopping</a>
        </div>
    </div>
@endsection
