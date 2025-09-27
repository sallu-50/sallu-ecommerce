@extends('layouts.app')

@section('content')
    <!-- Featured Products -->
    <div class="max-w-7xl mx-auto py-6 px-4">
        <h1 class="text-2xl sm:text-3xl font-bold mb-6">🌟 Featured Products</h1>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($featuredProducts as $product)
                <div class="bg-white border rounded-xl shadow hover:shadow-lg transition relative overflow-hidden">
                    @if ($product->discount_price)
                        <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                            {{ round(100 - ($product->discount_price / $product->price) * 100) }}% OFF
                        </div>
                    @endif
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                        class="h-40 sm:h-48 w-full object-cover rounded-t-xl">




                    <div class="p-4 flex flex-col justify-between">
                        <h2 class="text-lg font-semibold truncate">{{ $product->name }}</h2>
                        <p class="text-gray-700 mt-1">
                            ৳{{ $product->discount_price ?? $product->price }}
                            @if ($product->discount_price)
                                <span class="line-through text-red-500 text-sm">৳{{ $product->price }}</span>
                            @endif
                        </p>
                        <div class="mt-3 flex items-center justify-between">
                            <a href="{{ route('product.show', $product->id) }}"
                                class="text-blue-600 hover:underline text-sm">View</a>
                            <div class="flex items-center space-x-1">
                                <button onclick="addToCart({{ $product->id }})"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Add</button>
                                @auth
                                    @if ($product->isWishlisted())
                                        <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 text-lg">♥</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                            @csrf
                                            <button type="submit" class="text-gray-400 text-lg">♡</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Category Wise, Best Selling, New Arrivals Sections -->
    <div class="max-w-7xl mx-auto py-6 px-4 space-y-12">
        @foreach ($categories as $category)
            <section>
                <h2 class="text-2xl sm:text-3xl font-bold mb-6 border-b pb-2">{{ $category->name }}</h2>
                @if ($category->products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($category->products as $product)
                            <div
                                class="bg-white border rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                    class="h-40 sm:h-48 w-full object-cover">
                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold mb-1 truncate">{{ $product->name }}</h3>
                                        <p class="text-gray-600 font-medium mb-2">৳{{ $product->price }}</p>
                                    </div>
                                    <div class="flex items-center justify-between mt-auto">
                                        <a href="{{ route('product.show', $product->id) }}"
                                            class="text-blue-600 hover:underline text-sm">View</a>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="addToCart({{ $product->id }})"
                                                class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Add</button>
                                            @auth
                                                @if ($product->isWishlisted())
                                                    <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-500 text-lg">♥</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                                        @csrf
                                                        <button type="submit" class="text-gray-400 text-lg">♡</button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('category.show', $category->id) }}"
                            class="inline-block text-blue-500 hover:underline text-sm">See All {{ $category->name }}</a>
                    </div>
                @else
                    <p class="text-gray-500 italic">No products available in this category.</p>
                @endif
            </section>
        @endforeach
    </div>

    <!-- Best Selling -->
    <div class="max-w-7xl mx-auto py-6 px-4">
        <h1 class="text-2xl sm:text-3xl font-bold mb-6">🔥 Best Selling</h1>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($bestSellingProducts as $product)
                <div class="bg-white border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                        class="h-40 sm:h-48 w-full object-cover rounded-t-xl">
                    <div class="p-4 flex flex-col justify-between">
                        <h2 class="text-lg font-semibold truncate">{{ $product->name }}</h2>
                        <p class="text-gray-700 mt-1">৳{{ $product->price }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <a href="{{ route('product.show', $product->id) }}"
                                class="text-blue-600 hover:underline text-sm">View</a>
                            <div class="flex items-center space-x-1">
                                <button onclick="addToCart({{ $product->id }})"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Add</button>
                                @auth
                                    @if ($product->isWishlisted())
                                        <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 text-lg">♥</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                            @csrf
                                            <button type="submit" class="text-gray-400 text-lg">♡</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- New Arrivals -->
    <div class="max-w-7xl mx-auto py-6 px-4">
        <h1 class="text-2xl sm:text-3xl font-bold mb-6">🆕 New Arrivals</h1>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($newArrivals as $product)
                <div class="bg-white border rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                        class="h-40 sm:h-48 w-full object-cover rounded-t-xl">
                    <div class="p-4 flex flex-col justify-between">
                        <h2 class="text-lg font-semibold truncate">{{ $product->name }}</h2>
                        <p class="text-gray-700 mt-1">৳{{ $product->price }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <a href="{{ route('product.show', $product->id) }}"
                                class="text-blue-600 hover:underline text-sm">View</a>
                            <div class="flex items-center space-x-1">
                                <button onclick="addToCart({{ $product->id }})"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Add</button>
                                @auth
                                    @if ($product->isWishlisted())
                                        <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 text-lg">♥</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                            @csrf
                                            <button type="submit" class="text-gray-400 text-lg">♡</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
