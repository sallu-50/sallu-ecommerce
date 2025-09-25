@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full rounded-xl shadow">
        </div>

        <div>
            <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
            <p class="mt-2 text-gray-700">{{ $product->description }}</p>
            <p class="mt-4 text-xl font-semibold text-green-600">৳{{ $product->price }}</p>

            <div class="mt-4 flex flex-wrap items-center gap-4">
                <form method="POST" action="{{ route('cart.add') }}" class="space-y-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="flex items-center border rounded p-1 w-24 sm:w-32">
                        <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()"
                            class="flex-shrink-0 bg-gray-200 text-gray-700 hover:bg-gray-300 w-8 h-8 rounded-l focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">-</button>
                        <input type="number" name="quantity" value="1" min="1"
                            class="w-full text-center bg-transparent focus:outline-none" readonly>
                        <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()"
                            class="flex-shrink-0 bg-gray-200 text-gray-700 hover:bg-gray-300 w-8 h-8 rounded-r focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">+</button>
                    </div>
                    <button type="button" onclick="addToCart({{ $product->id }}, this.parentNode.querySelector('input[name=quantity]').value)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add to Cart</button>
                </form>

                @auth
                    @if ($product->isWishlisted())
                        <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Remove from Wishlist</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('wishlist.store', $product) }}">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700">+ Add to Wishlist</button>
                        </form>
                    @endif
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">+ Add to Wishlist</a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="max-w-4xl mx-auto py-8">
        <h2 class="text-xl font-bold mb-4">Customer Reviews</h2>

        <!-- Average Rating -->
        <div class="mb-6">
            @php
                $avgRating = $product->averageRating();
                $reviewCount = $product->reviews->count();
            @endphp
            <p class="text-lg font-semibold">Average Rating: {{ number_format($avgRating, 1) }} / 5 (based on {{ $reviewCount }} reviews)</p>
        </div>

        <!-- Review Submission Form -->
        @auth
            <div class="mb-8 p-4 border rounded-lg">
                <h3 class="font-semibold text-lg mb-2">Leave a Review</h3>
                <form action="{{ route('review.store', $product) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="rating" class="block mb-1">Your Rating</label>
                        <select name="rating" id="rating" class="border rounded p-2 w-full" required>
                            <option value="">Select a rating</option>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Very Good</option>
                            <option value="3">3 - Good</option>
                            <option value="2">2 - Fair</option>
                            <option value="1">1 - Poor</option>
                        </select>
                        @error('rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block mb-1">Your Comment</label>
                        <textarea name="comment" id="comment" rows="4" class="border rounded p-2 w-full" required></textarea>
                        @error('comment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit Review</button>
                </form>
            </div>
        @else
            <p class="mb-8"><a href="{{ route('login') }}" class="text-blue-600 underline">Log in</a> to leave a review.</p>
        @endauth

        <!-- Existing Reviews -->
        <div class="space-y-4">
            @forelse ($product->reviews as $review)
                <div class="p-4 border rounded-lg">
                    <p class="font-semibold">{{ $review->user->name }}</p>
                    <p class="text-sm text-gray-600">Rating: {{ $review->rating }} / 5</p>
                    <p class="mt-2">{{ $review->comment }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p>No reviews yet. Be the first to review this product!</p>
            @endforelse
        </div>
    </div>
@endsection
