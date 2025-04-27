@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded-xl shadow">
        </div>

        <div>
            <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
            <p class="mt-2 text-gray-700">{{ $product->description }}</p>
            <p class="mt-4 text-xl font-semibold text-green-600">৳{{ $product->price }}</p>

            <form method="POST" action="{{ route('cart.add') }}" class="mt-4 space-y-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="1" min="1" class="border rounded p-2 w-20">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add to
                    Cart</button>
            </form>
        </div>
    </div>
@endsection
