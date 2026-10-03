@extends('admin.layouts.admin')

@section('title', __('Products'))

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Products') }}</h1>
            <p class="text-gray-500">{{ __('Manage your product catalog') }}</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('Add Product') }}
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <form method="GET" class="flex flex-wrap gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search products...') }}" class="border-gray-200 rounded-lg text-sm">
                <select name="status" class="border-gray-200 rounded-lg text-sm">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>{{ __('Archived') }}</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium hover:bg-gray-200 transition">{{ __('Filter') }}</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Product') }}</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('SKU') }}</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Category') }}</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Price') }}</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Stock') }}</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                        @if($product->primaryImage)
                                            <img src="{{ $product->primaryImage->image_url }}" alt="" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->brand }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $product->sku }}</td>
                            <td class="px-4 py-3">{{ $product->category?->name }}</td>
                            <td class="px-4 py-3">
                                @if($product->has_discount)
                                    <span class="font-semibold text-primary-600">{{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-xs text-gray-400 line-through">{{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="font-semibold">{{ number_format($product->price, 2) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="{{ $product->is_in_stock ? 'text-green-600' : 'text-red-500' }}">{{ $product->stock }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $product->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $product->status === 'archived' ? 'bg-gray-100 text-gray-500' : '' }}">
                                    {{ __(ucfirst($product->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-primary-600 hover:text-primary-700">{{ __('Edit') }}</a>
                                    @if($product->status !== 'archived')
                                        <form action="{{ route('admin.products.archive', $product) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-700">{{ __('Archive') }}</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $products->links() }}
        </div>
    </div>
@endsection
