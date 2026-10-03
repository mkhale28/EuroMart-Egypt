<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>@yield('title', config('app.name')) - {{ __('Premium European Products') }}</title>
    <meta name="description" content="@yield('meta_description', __('EuroMart Egypt - Your trusted source for premium European products in Egypt. Shop cosmetics, shoes, bags, accessories, and more.'))">
    <meta name="keywords" content="@yield('meta_keywords', 'european products, imported goods, cosmetics, shoes, bags, egypt, online shopping')">

    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', __('Premium European Products in Egypt'))">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ url()->current() }}">

    @stack('structured_data')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="min-h-screen flex flex-col" x-data="{ cartCount: {{ $cartCount ?? 0 }} }">
        @include('layouts.navigation')

        <main class="flex-1">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-green-500 text-white text-center py-3 px-4">
                    <div class="max-w-7xl mx-auto flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button @click="show = false" class="text-white hover:text-green-200">&times;</button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-red-500 text-white text-center py-3 px-4">
                    <div class="max-w-7xl mx-auto flex justify-between items-center">
                        <span>{{ session('error') }}</span>
                        <button @click="show = false" class="text-white hover:text-red-200">&times;</button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('cart', {
                count: {{ $cartCount ?? 0 }},
                updateCount(count) {
                    this.count = count;
                }
            });
        });
    </script>
</body>
</html>
