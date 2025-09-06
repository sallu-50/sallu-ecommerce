@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">My Wishlist</h1>

        @if ($wishlistProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($wishlistProducts as $product)
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
                            <form action="{{ route('wishlist.destroy', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>Your wishlist is empty.</p>
            <a href="{{ route('home') }}" class="text-blue-600 underline mt-4 inline-block">Continue Shopping</a>
        @endif
    </div>
@endsection
