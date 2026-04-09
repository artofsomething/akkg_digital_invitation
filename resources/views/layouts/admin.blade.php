
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col fixed h-full">

        <!-- Logo -->
        <div class="p-6 border-b border-gray-700">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-white">
                🎉 DigiInvite
            </a>
            <p class="text-xs text-gray-400 mt-1">Admin Panel</p>
        </div>

        <!-- Menu -->
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm
                      {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                📊 Dashboard
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm
                      {{ request()->routeIs('admin.categories*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                📁 Categories
            </a>
            <a href="{{ route('admin.templates.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm
                      {{ request()->routeIs('admin.templates*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                🎨 Templates
            </a>
            <a href="{{ route('admin.users') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm
                      {{ request()->routeIs('admin.users*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                👥 Users
            </a>
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left text-sm text-red-400 hover:text-red-300 px-2 py-1">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64">

        <!-- Top Bar -->
        <header class="bg-white shadow-sm border-b border-gray-200 px-8 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">
                    @yield('title', 'Dashboard')
                </h1>
                <p class="text-sm text-gray-500">
                    {{ now()->format('l, d F Y') }}
                </p>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mx-8 mt-4"
             x-data="{ show: true }" x-show="show">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex justify-between">
                <span>✅ {{ session('success') }}</span>
                <button @click="show = false">✕</button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mx-8 mt-4"
             x-data="{ show: true }" x-show="show">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex justify-between">
                <span>❌ {{ session('error') }}</span>
                <button @click="show = false">✕</button>
            </div>
        </div>
        @endif

        <!-- Page Content -->
        <main class="p-8">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>