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
                            <td class="p-2">
                                <div class="flex items-center border rounded p-1 w-24 mx-auto">
                                    <button type="button" onclick="updateCartQuantity({{ $item['id'] }}, -1)"
                                        class="flex-shrink-0 bg-gray-200 text-gray-700 hover:bg-gray-300 w-6 h-6 rounded-l focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">-</button>
                                    <input type="number" value="{{ $item['quantity'] }}" min="1"
                                        class="w-full text-center bg-transparent focus:outline-none" readonly>
                                    <button type="button" onclick="updateCartQuantity({{ $item['id'] }}, 1)"
                                        class="flex-shrink-0 bg-gray-200 text-gray-700 hover:bg-gray-300 w-6 h-6 rounded-r focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">+</button>
                                </div>
                            </td>
                            <td class="p-2">৳{{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 flex justify-between items-center">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Clear Cart</button>
                </form>
                <a href="{{ route('filter.index') }}" class="text-blue-600 hover:underline">Continue Shopping</a>
            </div>

            <!-- Coupon Form -->
            <div class="mt-6 max-w-sm">
                <h2 class="text-lg font-semibold mb-2">Have a Coupon?</h2>
                @if (session()->has('coupon'))
                    <div class="flex justify-between items-center bg-green-100 p-2 rounded">
                        <span>Coupon Applied: <span class="font-bold">{{ session('coupon')['code'] }}</span></span>
                        <form action="{{ route('coupon.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 text-sm hover:underline">Remove</button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('coupon.store') }}" method="POST" class="flex">
                        @csrf
                        <input type="text" name="code" placeholder="Enter coupon code" class="border rounded-l p-2 w-full" required>
                        <button type="submit" class="bg-blue-600 text-white px-4 rounded-r hover:bg-blue-700">Apply</button>
                    </form>
                @endif
            </div>

            <div class="mt-4 text-right font-semibold space-y-1">
                <p>Subtotal: ৳{{ number_format($total, 2) }}</p>
                @if (session()->has('coupon'))
                    <p class="text-green-600">Discount: - ৳{{ number_format($discount, 2) }}</p>
                    <p class="text-xl">Total: ৳{{ number_format($newTotal, 2) }}</p>
                @else
                    <p class="text-xl">Total: ৳{{ number_format($total, 2) }}</p>
                @endif
            </div>

            <!-- Address Form for Checkout -->
            <form method="POST" action="{{ route('checkout') }}" class="mt-4 text-left">
                @csrf
                <div class="mb-4">
                    <label for="address" class="  font-semibold">Shipping Address</label>
                    <input type="text" name="address" id="address" class="w-full p-2 border border-gray-300 rounded"
                        required />
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Place Order
                </button>
            </form>
        @else
            <div class="text-center py-10">
                <p class="text-lg text-gray-600 mb-4">Your cart is empty.</p>
                <a href="{{ route('filter.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 text-lg">Continue Shopping</a>
            </div>
        @endif
    </div>

    <script>
        async function updateCartQuantity(productId, change) {
            const inputElement = document.querySelector(`input[type="number"][value="${document.querySelector(`button[onclick="updateCartQuantity(${productId}, ${change})`]
`).parentNode.querySelector('input[type="number"]').value}"]`);
            let newQuantity = parseInt(inputElement.value) + change;

            if (newQuantity < 0) newQuantity = 0; // Prevent negative quantity

            // Update the input value immediately for better UX
            inputElement.value = newQuantity;

            try {
                const response = await fetch('{{ route('cart.updateQuantity') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: newQuantity
                    })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    // Update the UI with new totals and cart items
                    const cartTableBody = document.querySelector('#cart-table tbody');
                    const subtotalElement = document.querySelector('.mt-4.text-right.font-semibold p:first-child');
                    const discountElement = document.querySelector('.mt-4.text-right.font-semibold p:nth-child(2)');
                    const totalElement = document.querySelector('.mt-4.text-right.font-semibold p:last-child');

                    // Re-render cart items if necessary (e.g., if an item was removed by setting quantity to 0)
                    if (Object.keys(data.cart_items).length === 0) {
                        window.location.reload(); // Reload if cart becomes empty
                    } else {
                        // Update totals
                        subtotalElement.innerHTML = `Subtotal: ৳${data.subtotal.toFixed(2)}`;
                        if (data.discount > 0) {
                            if (!discountElement) {
                                // Create discount element if it doesn't exist
                                const newDiscountP = document.createElement('p');
                                newDiscountP.className = 'text-green-600';
                                subtotalElement.parentNode.insertBefore(newDiscountP, totalElement);
                                discountElement = newDiscountP;
                            }
                            discountElement.innerHTML = `Discount: - ৳${data.discount.toFixed(2)}`;
                        } else if (discountElement) {
                            discountElement.remove(); // Remove if discount is 0
                        }
                        totalElement.innerHTML = `Total: ৳${data.newTotal.toFixed(2)}`;

                        // Update individual item subtotal in the table
                        const currentRow = inputElement.closest('tr');
                        const currentSubtotalCell = currentRow.querySelector('td:last-child');
                        const currentPrice = parseFloat(currentRow.querySelector('td:nth-child(2)').innerText.replace('৳', ''));
                        currentSubtotalCell.innerText = `৳${(currentPrice * newQuantity).toFixed(2)}`;
                    }

                    // Update cart count in navbar
                    document.getElementById('cart-count').innerText = data.cart_count;

                    showToast('Cart updated successfully!', 'success');
                } else {
                    showToast('Failed to update cart.', 'error');
                }
            } catch (error) {
                console.error('Error updating cart:', error);
                showToast('An error occurred.', 'error');
            }
        }
    </script>
@endsection
