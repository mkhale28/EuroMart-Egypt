@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    {{-- Hero Banner --}}
    <section class="relative bg-gradient-to-r from-primary-900 to-primary-700 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="relative z-10">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        {{ __('Premium European Products') }}<br>
                        <span class="text-primary-200">{{ __('Delivered to Your Doorstep') }}</span>
                    </h1>
                    <p class="text-lg md:text-xl text-primary-100 mb-8 leading-relaxed">
                        {{ __('Discover authentic European brands in cosmetics, fashion, and home products. Quality you can trust, prices you will love.') }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-white text-primary-700 px-8 py-3 rounded-lg font-bold hover:bg-primary-50 transition shadow-lg">
                            {{ __('Shop Now') }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-primary-500 transition border border-primary-400">
                            {{ __('View Sales') }}
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-800/50 to-transparent"></div>
                    <img src="{{ asset('images/hero-european-products.jpg') }}" alt="European Products" class="rounded-2xl shadow-2xl w-full h-auto object-cover" onerror="this.style.display='none'">
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    {{-- Categories --}}
    @if($categories->isNotEmpty())
        <section class="py-12 md:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('Shop by Category') }}</h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">{{ __('Browse our curated collection of European products') }}</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($categories as $category)
                        <a href="{{ route('products.category', $category->slug) }}" class="group bg-white rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-all border border-gray-100 hover:border-primary-200">
                            <div class="w-16 h-16 mx-auto mb-4 bg-primary-50 rounded-full flex items-center justify-center group-hover:bg-primary-100 transition">
                                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-primary-600 transition">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $category->active_products_count }} {{ __('Products') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured Products --}}
    @if($featuredProducts->isNotEmpty())
        <section class="py-12 md:py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('Featured Products') }}</h2>
                        <p class="text-gray-500">{{ __('Hand-picked premium European products') }}</p>
                    </div>
                    <a href="{{ route('products.index', ['featured' => 1]) }}" class="hidden sm:inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 font-semibold">
                        {{ __('View All') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach($featuredProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                <div class="text-center mt-8 sm:hidden">
                    <a href="{{ route('products.index', ['featured' => 1]) }}" class="inline-flex items-center gap-1 text-primary-600 font-semibold">
                        {{ __('View All Featured Products') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Promotions Banner --}}
    <section class="py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-2xl p-8 md:p-12 text-white text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ __('Special Offers') }}</h2>
                <p class="text-red-100 text-lg mb-8 max-w-2xl mx-auto">{{ __('Get amazing discounts on selected European products. Limited time offers.') }}</p>
                <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="inline-flex items-center gap-2 bg-white text-red-600 px-8 py-3 rounded-lg font-bold hover:bg-red-50 transition shadow-lg">
                    {{ __('Shop Sales') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- New Arrivals --}}
    @if($newArrivals->isNotEmpty())
        <section class="py-12 md:py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('New Arrivals') }}</h2>
                        <p class="text-gray-500">{{ __('Fresh from Europe') }}</p>
                    </div>
                    <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="hidden sm:inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 font-semibold">
                        {{ __('View All') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach($newArrivals as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Best Sellers --}}
    @if($bestSellers->isNotEmpty())
        <section class="py-12 md:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('Best Sellers') }}</h2>
                        <p class="text-gray-500">{{ __('Most popular European products') }}</p>
                    </div>
                    <a href="{{ route('products.index', ['sort' => 'best_seller']) }}" class="hidden sm:inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 font-semibold">
                        {{ __('View All') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach($bestSellers as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
        <section class="py-12 md:py-20 bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ __('What Our Customers Say') }}</h2>
                    <p class="text-gray-400 max-w-2xl mx-auto">{{ __('Real reviews from happy customers across Egypt') }}</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($testimonials as $testimonial)
                        <div class="bg-gray-800 rounded-xl p-6">
                            <div class="flex items-center gap-1 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <p class="text-gray-300 mb-4 leading-relaxed">"{{ $testimonial->content }}"</p>
                            <div>
                                <p class="font-semibold">{{ $testimonial->customer_name }}</p>
                                @if($testimonial->customer_title)
                                    <p class="text-sm text-gray-500">{{ $testimonial->customer_title }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Features --}}
    <section class="py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto mb-4 bg-primary-50 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ __('European Quality') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('100% authentic European products') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto mb-4 bg-primary-50 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ __('Fast Delivery') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Delivery across all Egypt') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto mb-4 bg-primary-50 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ __('Secure Shopping') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Safe and secure payments') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto mb-4 bg-primary-50 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ __('24/7 Support') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Always here to help you') }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
