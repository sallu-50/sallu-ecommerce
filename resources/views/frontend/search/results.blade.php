@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">Search Results for "<span class="text-blue-600">{{ $query }}</span>"</h1>

        @if ($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="border rounded-lg p-4 text-center">
                        <a href="{{ route('product.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-md mb-4">
                            <h2 class="font-semibold">{{ $product->name }}</h2>
                            <p class="text-green-600 font-bold">৳{{ $product->price }}</p>
                        </a>
                        <div class="mt-4 flex justify-center items-center space-x-2">
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Add to Cart</button>
                            </form>
                            @auth
                                @if ($product->isWishlisted())
                                    <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove from Wishlist" class="text-red-500">♥</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                        @csrf
                                        <button type="submit" title="Add to Wishlist" class="text-gray-400">♡</button>
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
            <p>No products found matching your search criteria.</p>
            <a href="{{ route('home') }}" class="text-blue-600 underline mt-4 inline-block">Continue Shopping</a>
        @endif
    </div>
@endsection
