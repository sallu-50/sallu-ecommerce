@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6">
        <h1 class="text-2xl font-bold mb-4">All Products</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="border p-4 rounded-xl shadow hover:shadow-lg transition">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="h-48 w-full object-cover rounded">
                    <h2 class="mt-2 text-lg font-semibold">{{ $product->name }}</h2>
                    <p class="text-gray-600">৳{{ $product->price }}</p>
                    <a href="{{ route('product.show', $product->id) }}"
                        class="mt-2 inline-block text-blue-600 hover:underline">View Details</a>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
@endsection
