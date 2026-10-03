<header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">
    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('locale.switch', 'ar') }}" class="px-2 py-1 text-sm {{ app()->getLocale() === 'ar' ? 'text-primary-600 font-bold' : 'text-gray-400' }}">العربية</a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('locale.switch', 'en') }}" class="px-2 py-1 text-sm {{ app()->getLocale() === 'en' ? 'text-primary-600 font-bold' : 'text-gray-400' }}">EN</a>
        </div>

        <div class="flex items-center gap-3" x-data="{ open: false }">
            <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-600 transition">{{ __('Logout') }}</button>
            </form>
        </div>
    </div>
</header>
