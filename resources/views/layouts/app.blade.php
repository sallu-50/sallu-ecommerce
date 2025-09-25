<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sallu's Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Professional Responsive Navbar -->
    <nav x-data="{ open: false }" class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo + Desktop Links -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">Sallu's Shop</a>
                    <div class="hidden md:flex space-x-4">
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-nav-link>
                        <x-nav-link :href="route('filter.index')" :active="request()->routeIs('filter.index')">Shop</x-nav-link>
                    </div>
                </div>

                <!-- Desktop: Search -->
                <div class="hidden md:flex flex-1 justify-center px-4">
                    <x-search-bar />
                </div>

                <!-- Desktop: Auth / Cart -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <span class="text-sm font-medium text-gray-800">Hi, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Login</a>
                        <a href="{{ route('register') }}"
                            class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Register</a>
                    @endauth
                    <a href="{{ route('cart.view') }}"
                        class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Cart <span id="cart-count"
                            class="ml-1 px-2 py-0.5 bg-white text-green-600 rounded-full text-xs font-bold">0</span>
                    </a>
                </div>

                <!-- Mobile Hamburger -->
                <div class="flex md:hidden">
                    <button @click="open = !open"
                        class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{ 'block': open, 'hidden': !open }"
            class="hidden md:hidden bg-white shadow-lg px-4 pt-4 pb-2 space-y-3">
            {{-- <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 block text-center">Sallu's Shop</a> --}}

            <!-- Mobile Auth + Cart -->
            <div class="flex justify-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="px-3 py-1 bg-blue-600 text-white rounded-md">Login</a>
                    <a href="{{ route('register') }}" class="px-3 py-1 bg-green-600 text-white rounded-md">Register</a>
                @else
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md">Logout</button>
                    </form>
                @endguest
                <a href="{{ route('cart.view') }}" class="px-3 py-1 bg-green-600 text-white rounded-md">
                    Cart <span id="cart-count-mobile"
                        class="ml-1 px-2 py-0.5 bg-white text-green-600 rounded-full text-xs font-bold">0</span>
                </a>
            </div>

            <!-- Mobile Search + Filter -->
            <div class="mt-2">
                <x-search-bar />
            </div>
            {{-- <div class="mt-2 bg-gray-50 p-3 rounded-lg">
                <form id="mobileFilterForm" class="grid grid-cols-1 gap-2">
                    <input type="text" name="search" placeholder="Search product..."
                        class="border p-2 rounded w-full" />
                    <select name="category_id" class="border p-2 rounded w-full">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="min_price" placeholder="Min Price" class="border p-2 rounded w-full" />
                    <input type="number" name="max_price" placeholder="Max Price" class="border p-2 rounded w-full" />
                    <button type="submit" class="bg-blue-600 text-white p-2 rounded w-full">Filter</button>
                </form>
            </div> --}}
        </div>
    </nav>

    <!-- Side Cart -->
    <div id="side-cart" class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg border-l p-4 z-50 hidden">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Cart</h2>
            <button onclick="closeSideCart()"
                class="text-gray-500 hover:text-red-600 text-2xl font-bold">&times;</button>
        </div>
        <ul id="side-cart-items" class="space-y-2 overflow-y-auto max-h-[60vh] pr-2"></ul>
        <div class="mt-4 font-semibold" id="side-cart-total">Total: ৳0.00</div>
        <a href="{{ route('cart.view') }}"
            class="block mt-4 text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            View Cart
        </a>
    </div>

    <main class="px-4">
        @yield('content')
    </main>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Toast
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `p-3 rounded-md shadow-md text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            toast.textContent = message;
            toastContainer.appendChild(toast);
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Side Cart
        function closeSideCart() {
            document.getElementById('side-cart').classList.add('hidden');
        }

        function updateSideCartUI(data) {
            const container = document.getElementById('side-cart-items');
            const cartCountSpan = document.getElementById('cart-count');
            const cartCountMobile = document.getElementById('cart-count-mobile');
            container.innerHTML = '';
            let total = 0;
            for (let key in data.cart_items) {
                const item = data.cart_items[key];
                const subtotal = item.price * item.quantity;
                total += subtotal;
                container.innerHTML += `
                    <li class="border-b py-2">
                        <div class="font-medium">${item.name}</div>
                        <div class="text-sm text-gray-600">৳${item.price} × ${item.quantity}</div>
                    </li>
                `;
            }
            document.getElementById('side-cart-total').innerText = `Total: ৳${total.toFixed(2)}`;
            document.getElementById('side-cart').classList.remove('hidden');
            cartCountSpan.innerText = cartCountMobile.innerText = data.cart_count;
        }

        // Add to Cart (AJAX)
        function addToCart(productId, quantity = 1) {
            fetch("{{ route('cart.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateSideCartUI(data);
                        showToast('Product added to cart!', 'success');
                    } else {
                        showToast('Something went wrong.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Error adding to cart.', 'error');
                });
        }
    </script>

    @stack('scripts')
</body>

</html>
