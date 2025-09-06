<!-- resources/views/products/index.blade.php -->

{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Products sallasja</h1>

    {{-- <form method="GET" action="{{ route('filter.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Category Filter -->
            <select name="category" class="border rounded p-2">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <!-- Min Price -->
            <input type="number" name="min_price" placeholder="Min Price" value="{{ request('min_price') }}"
                class="border rounded p-2">

            <!-- Max Price -->
            <input type="number" name="max_price" placeholder="Max Price" value="{{ request('max_price') }}"
                class="border rounded p-2">

            <!-- Filter Button -->
            <button type="submit" class="bg-blue-500 text-white rounded p-2 hover:bg-blue-600">Filter</button>
        </form> --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="border p-4 rounded-xl shadow hover:shadow-lg transition">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                    class="h-48 w-full object-cover rounded">
                <h2 class="mt-2 text-lg font-semibold">{{ $product->name }}</h2>
                <p class="text-gray-600">৳{{ $product->price }}</p>
                <a href="{{ route('product.show', $product->id) }}"
                    class="mt-2 inline-block text-blue-600 hover:underline">View Details</a>
                <div class="mt-2 flex items-center space-x-2">
                    <input type="number" id="quantity-{{ $product->id }}" value="1" min="1" class="border rounded p-1 w-16 text-center">
                    <button onclick="addToCart({{ $product->id }}, document.getElementById('quantity-{{ $product->id }}').value)" class="bg-blue-600 text-white px-3 py-1 rounded">Add to Cart</button>
                </div>
            </div>
        @empty
            <p>No products found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>

</div>
{{-- @endsection --}}
