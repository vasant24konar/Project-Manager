<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Product Manager') }} - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css">
    <style>
        .ql-editor { min-height: 150px; }
        .ql-container { font-size: 1rem; }
    </style>
</head>
<body class="h-full">
<div class="min-h-full">
    <nav class="bg-indigo-700 shadow">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-8">
                    <a href="{{ route('products.index') }}" class="text-white font-bold text-lg tracking-tight">
                        ProductManager
                    </a>
                    @auth
                    <div class="flex gap-4">
                        <a href="{{ route('products.index') }}"
                           class="text-indigo-100 hover:text-white text-sm font-medium {{ request()->routeIs('products.*') ? 'text-white underline underline-offset-4' : '' }}">
                            Products
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}"
                           class="text-indigo-100 hover:text-white text-sm font-medium {{ request()->routeIs('admin.*') ? 'text-white underline underline-offset-4' : '' }}">
                            Users
                        </a>
                        @endif
                    </div>
                    @endauth
                </div>
                <div class="flex items-center gap-4">
                    @auth
                    <span class="text-indigo-200 text-sm">
                        {{ auth()->user()->name }}
                        <span class="ml-1 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            {{ auth()->user()->isAdmin() ? 'bg-yellow-100 text-yellow-800' : 'bg-indigo-100 text-indigo-800' }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-indigo-200 hover:text-white text-sm font-medium">
                            Log out
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="text-indigo-200 hover:text-white text-sm font-medium">Log in</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl py-8 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 flex items-start gap-3">
            <svg class="h-5 w-5 text-green-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
