<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>E-commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="bg-white shadow py-4 mb-2 mx-auto px-8 flex flex-wrap justify-between items-center gap-4">
        <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">Sallu's Shop</a>



        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18l-1 10H4L3 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 21a2 2 0 100-4 2 2 0 000 4zM8 21a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    My Orders
                </a>

            @endauth

            <a href="{{ route('cart.view') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m6-9v9m2-13a1 1 0 100-2 1 1 0 000 2z" />
                </svg>
                View Cart
            </a>



            @auth
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-800">🧑‍💻 {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="text-sm px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}"
                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-3 py-1 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Register
                    </a>
                </div>
            @endauth


        </div>
    </div>
    <div class="bg-white p-2 rounded-xl shadow mb-6">
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Search product..." class="border p-2 rounded w-full" />

            <select name="category_id" class="border p-2 rounded w-full">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <input type="number" name="min_price" placeholder="Min Price" class="border p-2 rounded w-full" />
            <input type="number" name="max_price" placeholder="Max Price" class="border p-2 rounded w-full" />

            <button type="submit" class="bg-blue-600 text-white p-2 rounded col-span-1 md:col-span-4">Filter</button>
        </form>

        <div id="activeFilters" class="mt-4 flex flex-wrap gap-2">
            <!-- Active filters will be injected here -->
        </div>
    </div>


    <main class="px-4">
        @if (session('success'))
            <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4 max-w-4xl mx-auto">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4 max-w-4xl mx-auto">
                {{ session('error') }}
            </div>
        @endif

        <main class="px-4">
            <div id="productsList">
                @yield('content')
            </div>
        </main>

    </main>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            const productsList = document.getElementById('productsList');
            const activeFilters = document.getElementById('activeFilters');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const queryString = new URLSearchParams(formData).toString();

                fetch("{{ route('filter.index') }}?" + queryString, {

                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        productsList.innerHTML = html;
                        showActiveFilters(formData);
                    });
            });

            function showActiveFilters(formData) {
                activeFilters.innerHTML = '';

                for (const [key, value] of formData.entries()) {
                    if (value.trim() !== '') {
                        const badge = document.createElement('span');
                        badge.className =
                            "bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded";
                        badge.innerText = `${key}: ${value}`;
                        activeFilters.appendChild(badge);
                    }
                }

                if (formData.has('search') || formData.has('category_id') || formData.has('min_price') || formData
                    .has('max_price')) {
                    const clearBtn = document.createElement('button');
                    clearBtn.innerText = 'Clear Filters';
                    clearBtn.className = 'bg-red-500 text-white px-3 py-1 rounded text-xs';
                    clearBtn.onclick = () => {
                        form.reset();
                        form.dispatchEvent(new Event('submit'));
                    };
                    activeFilters.appendChild(clearBtn);
                }
            }
        });
    </script>


</body>

</html>
