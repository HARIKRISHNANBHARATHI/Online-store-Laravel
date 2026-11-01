<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Online Store') }} - Premium E-Commerce</title>

    <!-- Tailwind CSS CDN for instant rendering -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased">
    <!-- Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand Logo -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('products.index') }}" class="flex items-center space-x-2 text-indigo-600 font-bold text-xl tracking-tight">
                        <i class="fa-solid fa-bag-shopping text-2xl"></i>
                        <span>NEXUS<span class="text-slate-900">STORE</span></span>
                    </a>
                    <nav class="hidden md:flex space-x-6 text-sm font-medium">
                        <a href="{{ route('products.index') }}" class="text-slate-700 hover:text-indigo-600 transition">Shop All</a>
                        @foreach(\App\Models\Category::where('status', true)->take(4)->get() as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}" class="text-slate-600 hover:text-indigo-600 transition">{{ $cat->name }}</a>
                        @endforeach
                    </nav>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center space-x-4 sm:space-x-6">
                    <!-- Search Input -->
                    <form action="{{ route('products.index') }}" method="GET" class="hidden md:block relative w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products, SKU..." class="w-full bg-slate-100 text-slate-800 text-xs rounded-full pl-9 pr-4 py-2 border-0 focus:ring-2 focus:ring-indigo-600">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    </form>

                    <!-- Wishlist Link -->
                    @auth
                        @php
                            $wishlistCount = auth()->user()->wishlist ? auth()->user()->wishlist->items()->count() : 0;
                        @endphp
                        <a href="{{ route('wishlist.index') }}" class="relative text-slate-600 hover:text-rose-600 transition">
                            <i class="fa-regular fa-heart text-lg"></i>
                            @if($wishlistCount > 0)
                                <span class="absolute -top-1.5 -right-2 bg-rose-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $wishlistCount }}</span>
                            @endif
                        </a>
                    @endauth

                    <!-- Cart Link -->
                    @php
                        $cartService = app(\App\Services\CartService::class);
                        $cartSummary = $cartService->getSummary();
                    @endphp
                    <a href="{{ route('cart.index') }}" class="relative text-slate-600 hover:text-indigo-600 transition flex items-center space-x-2">
                        <div class="relative">
                            <i class="fa-solid fa-cart-shopping text-lg"></i>
                            @if($cartSummary['item_count'] > 0)
                                <span class="absolute -top-2 -right-2.5 bg-indigo-600 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $cartSummary['item_count'] }}</span>
                            @endif
                        </div>
                        <span class="hidden sm:inline text-xs font-semibold text-slate-700">${{ number_format($cartSummary['total'], 2) }}</span>
                    </a>

                    <!-- User Menu / Auth -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-xs flex items-center justify-center border border-indigo-200">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </span>
                                <span class="hidden sm:block text-xs font-medium text-slate-700">{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-100 py-1 z-50">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs text-indigo-600 font-semibold hover:bg-slate-50"><i class="fa-solid fa-gauge mr-2"></i>Admin Dashboard</a>
                                    <div class="border-t border-slate-100 my-1"></div>
                                @endif
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-box mr-2"></i>My Orders</a>
                                <a href="{{ route('addresses.index') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-location-dot mr-2"></i>Saved Addresses</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-user-gear mr-2"></i>Account Settings</a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium"><i class="fa-solid fa-right-from-bracket mr-2"></i>Sign Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-2 text-xs font-semibold">
                            <a href="{{ route('login') }}" class="text-slate-700 hover:text-indigo-600 px-3 py-1.5 rounded-md transition">Log In</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-3.5 py-1.5 rounded-md hover:bg-indigo-700 transition shadow-sm">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg text-sm flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-bold text-lg mb-3">NEXUS STORE</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">A modern Laravel online store portfolio project featuring clean architecture, REST APIs, Stripe checkout, and database transactions.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition">All Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition">Shopping Cart</a></li>
                        <li><a href="{{ route('orders.index') }}" class="hover:text-white transition">Order History</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Demo Accounts</h4>
                    <div class="text-xs space-y-1">
                        <p><strong class="text-slate-300">Admin:</strong> admin@example.com</p>
                        <p><strong class="text-slate-300">Customer:</strong> customer@example.com</p>
                        <p><strong class="text-slate-300">Password:</strong> password</p>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Payment Methods</h4>
                    <p class="text-xs text-slate-400 mb-2">Integrated with Stripe Test Payments</p>
                    <div class="flex space-x-3 text-xl text-slate-400">
                        <i class="fa-brands fa-cc-stripe hover:text-white"></i>
                        <i class="fa-brands fa-cc-visa hover:text-white"></i>
                        <i class="fa-brands fa-cc-mastercard hover:text-white"></i>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-6 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} NEXUS STORE. Built with Laravel {{ app()->version() }} & Tailwind CSS.
            </div>
        </div>
    </footer>
</body>
</html>
