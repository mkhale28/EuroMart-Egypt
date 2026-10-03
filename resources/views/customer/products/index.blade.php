@extends('layouts.app')

@section('title', __('Products') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Filters Sidebar --}}
            <aside class="lg:w-64 shrink-0">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4">{{ __('Filters') }}</h3>

                    {{-- Search --}}
                    <form action="{{ route('products.index') }}" method="GET" class="mb-6">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search products...') }}" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </form>

                    {{-- Categories --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3">{{ __('Categories') }}</h4>
                        <div class="space-y-2">
                            <a href="{{ route('products.index') }}" class="block text-sm {{ !request('category') ? 'text-primary-600 font-semibold' : 'text-gray-600 hover:text-primary-600' }} transition">{{ __('All Categories') }}</a>
                            @foreach($categories as $category)
                                <a href="{{ route('products.category', $category->slug) }}" class="block text-sm {{ request('category') === $category->slug ? 'text-primary-600 font-semibold' : 'text-gray-600 hover:text-primary-600' }} transition">
                                    {{ $category->name }}
                                    <span class="text-gray-400">({{ $category->active_products_count }})</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Brands --}}
                    @if(isset($brands) && $brands->isNotEmpty())
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">{{ __('Brands') }}</h4>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($brands as $brand)
                                    <a href="{{ request()->fullUrlWithQuery(['brand' => $brand]) }}" class="block text-sm {{ request('brand') === $brand ? 'text-primary-600 font-semibold' : 'text-gray-600 hover:text-primary-600' }} transition">
                                        {{ $brand }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Price Range --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3">{{ __('Price Range') }}</h4>
                        <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                            @foreach(request()->except(['min_price', 'max_price', 'page']) as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="{{ __('Min') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="{{ __('Max') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <button type="submit" class="px-3 py-2 bg-primary-600 text-white rounded-lg text-sm hover:bg-primary-700 transition">{{ __('Go') }}</button>
                        </form>
                    </div>

                    {{-- On Sale --}}
                    <div class="mb-4">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="on_sale" value="1" {{ request('on_sale') ? 'checked' : '' }} onchange="window.location.href='{{ request()->fullUrlWithQuery(['on_sale' => request('on_sale') ? null : 1]) }}'" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span>{{ __('On Sale Only') }}</span>
                        </label>
                    </div>

                    <button onclick="window.location.href='{{ route('products.index') }}'" class="w-full text-sm text-gray-500 hover:text-primary-600 transition">{{ __('Clear All Filters') }}</button>
                </div>
            </aside>

            {{-- Products Grid --}}
            <div class="flex-1">
                {{-- Sort & Count --}}
                <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                    <p class="text-gray-500 text-sm">{{ $products->total() }} {{ __('products found') }}</p>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-500">{{ __('Sort by') }}:</label>
                        <select onchange="window.location.href='{{ request()->fullUrlWithQuery(['sort' => '__value__']) }}'.replace('__value__', this.value)" class="text-sm border-gray-200 rounded-lg focus:ring-primary-500">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>{{ __('Latest') }}</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>{{ __('Name: A-Z') }}</option>
                            <option value="best_seller" {{ request('sort') === 'best_seller' ? 'selected' : '' }}>{{ __('Best Seller') }}</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Products --}}
                @if($products->isEmpty())
                    <div class="text-center py-20">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No products found') }}</h3>
                        <p class="text-gray-500">{{ __('Try adjusting your filters or search terms') }}</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <x-pagination :paginator="$products" />
                @endif
            </div>
        </div>
    </div>
@endsection
