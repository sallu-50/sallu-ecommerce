<!-- resources/views/layouts/navigation.blade.php -->
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Desktop Left: Logo + Menu -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    {{-- <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a> --}}
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden sm:flex sm:ms-10 space-x-8">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-nav-link>
                    <x-nav-link :href="route('filter.index')" :active="request()->routeIs('filter.index')">Shop</x-nav-link>
                </div>
            </div>

            <!-- Desktop: Search bar -->
            <div class="hidden sm:flex justify-center flex-grow">
                <x-search-bar />
            </div>

            <!-- Desktop: Profile/Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('wishlist.index')">My Wishlist</x-dropdown-link>
                        <x-dropdown-link :href="route('orders.index')">My Orders</x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">Log
                                Out</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="flex sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden text-center">
        <!-- Logo -->
        <div class="py-2">
            <a href="{{ route('home') }}">
                <x-application-logo class="mx-auto h-12 w-auto fill-current text-gray-800" />
            </a>
        </div>

        <!-- Auth Buttons -->
        <div class="flex justify-center space-x-2 mb-2">
            @guest
                <a href="{{ route('login') }}" class="px-3 py-1 bg-blue-600 text-white rounded">Login</a>
                <a href="{{ route('register') }}" class="px-3 py-1 bg-green-600 text-white rounded">Register</a>
            @else
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Logout</button>
                </form>
                <a href="{{ route('cart.index') }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Cart</a>
            @endguest
        </div>

        <!-- Search Bar -->
        <div class="px-4 py-2">
            <x-search-bar />
        </div>

        <!-- Filter (Mobile only) -->
        <div class="px-4 py-2">
            <x-mobile-filter />
        </div>
    </div>
</nav>
