@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-6">
        <h1 class="text-2xl font-bold mb-4">Your Cart</h1>

        @if (count($cart) > 0)
            <table class="w-full table-auto border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Product</th>
                        <th class="p-2">Price</th>
                        <th class="p-2">Quantity</th>
                        <th class="p-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cart as $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr class="border-b">
                            <td class="p-2">{{ $item['name'] }}</td>
                            <td class="p-2">৳{{ number_format($item['price'], 2) }}</td>
                            <td class="p-2">{{ $item['quantity'] }}</td>
                            <td class="p-2">৳{{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 text-right font-semibold">
                Total: ৳{{ number_format($total, 2) }}
            </div>

            <!-- Address Form for Checkout -->
            <form method="POST" action="{{ route('checkout') }}" class="mt-4 text-left">
                @csrf
                <div class="mb-4">
                    <label for="address" class="  font-semibold">Shipping Address</label>
                    <input type="text" name="address" id="address" class="w-full p-2 border border-gray-300 rounded"
                        required />
                </div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Place Order
                </button>
            </form>
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>
@endsection
