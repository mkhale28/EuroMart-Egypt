<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-primary-600">{{ config('app.name') }}</span>
                    <span class="hidden sm:inline text-xs bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full font-medium">{{ __('Since 2024') }}</span>
                </a>

                <nav class="hidden lg:flex items-center gap-6">
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-primary-600 font-medium transition">{{ __('All Products') }}</a>
                    <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="text-red-500 hover:text-red-600 font-medium transition">{{ __('Sale') }}</a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center">
                    <a href="{{ route('locale.switch', 'ar') }}" class="px-2 py-1 text-sm {{ app()->getLocale() === 'ar' ? 'text-primary-600 font-bold' : 'text-gray-400' }} {{ app()->getLocale() === 'ar' ? 'border-b-2 border-primary-600' : '' }}">العربية</a>
                    <span class="text-gray-300 mx-1">|</span>
                    <a href="{{ route('locale.switch', 'en') }}" class="px-2 py-1 text-sm {{ app()->getLocale() === 'en' ? 'text-primary-600 font-bold' : 'text-gray-400' }} {{ app()->getLocale() === 'en' ? 'border-b-2 border-primary-600' : '' }}">EN</a>
                </div>

                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-primary-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    <span x-text="cartCount" class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-bold"></span>
                </a>

                @auth
                    <div class="hidden sm:flex items-center gap-3" x-data="{ open: false }">
                        <a href="{{ route('dashboard.index') }}" class="text-gray-600 hover:text-primary-600 font-medium transition">{{ __('Dashboard') }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-red-500 transition">{{ __('Logout') }}</button>
                        </form>
                    </div>
                @else
                    <div class="hidden sm:flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-600 font-medium transition">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition font-medium">{{ __('Register') }}</a>
                    </div>
                @endauth

                <button class="lg:hidden p-2 text-gray-600" x-data @click="$dispatch('toggle-mobile-menu')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" x-cloak class="lg:hidden border-t bg-white">
        <div class="px-4 py-3 space-y-3">
            <a href="{{ route('products.index') }}" class="block text-gray-600 hover:text-primary-600 font-medium">{{ __('All Products') }}</a>
            <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="block text-red-500 hover:text-red-600 font-medium">{{ __('Sale') }}</a>
            @auth
                <a href="{{ route('dashboard.index') }}" class="block text-gray-600 hover:text-primary-600 font-medium">{{ __('Dashboard') }}</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block text-gray-600 hover:text-primary-600 font-medium">{{ __('Admin Panel') }}</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block text-red-500 font-medium">{{ __('Logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-gray-600 hover:text-primary-600 font-medium">{{ __('Login') }}</a>
                <a href="{{ route('register') }}" class="block text-primary-600 font-medium">{{ __('Register') }}</a>
            @endauth
            <div class="flex gap-2 pt-2 border-t">
                <a href="{{ route('locale.switch', 'ar') }}" class="px-3 py-1 rounded {{ app()->getLocale() === 'ar' ? 'bg-primary-100 text-primary-700' : 'text-gray-500' }}">العربية</a>
                <a href="{{ route('locale.switch', 'en') }}" class="px-3 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-primary-100 text-primary-700' : 'text-gray-500' }}">English</a>
            </div>
        </div>
    </div>
</header>
