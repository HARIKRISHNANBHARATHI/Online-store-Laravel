<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - {{ config('app.name', 'Online Store') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="h-full flex text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Admin Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col min-h-screen">
        <div class="h-16 flex items-center px-6 bg-slate-950 font-bold text-lg text-white border-b border-slate-800">
            <i class="fa-solid fa-user-shield text-indigo-500 mr-3"></i> ADMIN PANEL
        </div>
        <nav class="flex-grow py-4 px-3 space-y-1 text-sm font-medium">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white font-semibold' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-line w-6 text-center text-xs mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white font-semibold' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-box-archive w-6 text-center text-xs mr-2"></i> Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white font-semibold' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-layer-group w-6 text-center text-xs mr-2"></i> Categories
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white font-semibold' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-receipt w-6 text-center text-xs mr-2"></i> Orders
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.customers.*') ? 'bg-indigo-600 text-white font-semibold' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users w-6 text-center text-xs mr-2"></i> Customers
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.coupons.*') ? 'bg-indigo-600 text-white font-semibold' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-ticket w-6 text-center text-xs mr-2"></i> Coupons
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white font-semibold' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-6 text-center text-xs mr-2"></i> Reports
            </a>

            <div class="pt-6 mt-6 border-t border-slate-800">
                <a href="{{ route('products.index') }}" class="flex items-center px-3 py-2 text-xs text-indigo-400 hover:text-indigo-300">
                    <i class="fa-solid fa-store mr-2"></i> Back to Main Store
                </a>
            </div>
        </nav>
        <div class="p-4 border-t border-slate-800 text-xs text-slate-400 flex items-center justify-between">
            <span>Logged in as Admin</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-rose-400 hover:text-rose-300"><i class="fa-solid fa-power-off"></i></button>
            </form>
        </div>
    </aside>

    <!-- Main Content Body -->
    <div class="flex-grow flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Navigation -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between">
            <h1 class="text-lg font-bold text-slate-800">@yield('title', 'Admin Area')</h1>
            <div class="flex items-center space-x-3 text-xs">
                <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-full font-semibold border border-indigo-100">
                    <i class="fa-solid fa-circle text-[8px] text-emerald-500 mr-1.5"></i> Production Ready
                </span>
            </div>
        </header>

        <!-- Alerts -->
        <div class="px-6 mt-4">
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <main class="p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
