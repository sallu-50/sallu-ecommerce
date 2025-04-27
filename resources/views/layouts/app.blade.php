<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>E-commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="bg-white shadow py-4 mb-6  mx-auto px-8 flex justify-between">
        <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">Shop</a>

        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:underline">My Orders</a>
            @endauth

            <a href="{{ route('cart.view') }}" class="text-blue-600 hover:underline">Cart</a>

            @auth
                <span class="text-sm">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="text-red-600 text-sm hover:underline">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Login</a>
                <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">Register</a>
            @endauth
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

        @yield('content')
    </main>
</body>

</html>
