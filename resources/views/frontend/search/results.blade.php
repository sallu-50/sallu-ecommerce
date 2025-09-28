@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">
            Search Results for "<span class="text-blue-600">{{ $query }}</span>"
        </h1>

        @if ($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="border rounded-lg p-4 text-center">
                        <a href="{{ route('product.show', $product->id) }}">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="w-full h-48 object-cover rounded-md mb-4">
                            <h2 class="font-semibold">{{ $product->name }}</h2>
                            <p class="text-green-600 font-bold">৳{{ $product->price }}</p>
                        </a>

                        <div class="mt-4 flex justify-center items-center space-x-2">
                            {{-- Add to Cart --}}
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="number" id="quantity-{{ $product->id }}" value="1" min="1"
                                    class="border rounded p-1 w-16 text-center">
                                <button type="button"
                                    onclick="addToCart({{ $product->id }}, document.getElementById('quantity-{{ $product->id }}').value)"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Add to Cart
                                </button>
                            </form>

                            {{-- Wishlist --}}
                            @auth
                                @if ($product->isWishlisted())
                                    <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove from Wishlist" class="text-red-500">
                                            ♥
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                        @csrf
                                        <button type="submit" title="Add to Wishlist" class="text-gray-400">
                                            ♡
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->appends(request()->input())->links() }}
            </div>
        @else
            <div class="text-center py-10">
                <p class="text-lg text-gray-600 mb-4">
                    No products found matching your search criteria.
                </p>
                <a href="{{ route('filter.index') }}"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 text-lg">
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>
@endsection
