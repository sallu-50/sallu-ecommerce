@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Filters Sidebar -->
        <aside class="md:col-span-1">
            <h2 class="text-xl font-bold mb-4">Filters</h2>
            <form id="filter-form" action="{{ route('filter.index') }}" method="GET">
                <!-- Category Filter -->
                <div class="mb-4">
                    <label for="category" class="font-semibold block mb-2">Category</label>
                    <select name="category_id" id="category" class="border rounded p-2 w-full">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Filter -->
                <div class="mb-4">
                    <label class="font-semibold block mb-2">Price Range</label>
                    <div class="flex space-x-2">
                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="border rounded p-2 w-full">
                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="border rounded p-2 w-full">
                    </div>
                </div>

                <!-- Sorting -->
                <div class="mb-4">
                    <label for="sort" class="font-semibold block mb-2">Sort By</label>
                    <select name="sort" id="sort" class="border rounded p-2 w-full">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 text-white rounded p-2 w-full hover:bg-blue-700">Apply Filters</button>
            </form>
        </aside>

        <!-- Product Grid -->
        <main class="md:col-span-3">
            <div id="product-list">
                @include('frontend.filter.index', ['products' => $products])
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('filter-form');
        const productListContainer = document.getElementById('product-list');

        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            updateProducts();
        });

        // Also update on change of select dropdowns
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                updateProducts();
            });
        });

        function updateProducts() {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            const url = `{{ route('filter.index') }}?${params.toString()}`;

            // Update URL in browser
            window.history.pushState({}, '', url);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.text())
            .then(html => {
                productListContainer.innerHTML = html;
            })
            .catch(error => console.error('Error fetching products:', error));
        }

        // Handle pagination clicks
        document.addEventListener('click', function(e) {
            if (e.target.matches('#product-list .pagination a')) {
                e.preventDefault();
                const url = e.target.href;
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    productListContainer.innerHTML = html;
                    window.history.pushState({}, '', url);
                })
                .catch(error => console.error('Error fetching paginated products:', error));
            }
        });
    });
</script>
@endpush
