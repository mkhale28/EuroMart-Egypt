@extends('admin.layouts.admin')

@section('title', __('Edit Product'))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Edit Product') }}</h1>
        <p class="text-gray-500">{{ $product->name }}</p>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 max-w-4xl">
        @csrf @method('PUT')
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Product Name') }} *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Category') }} *</label>
                <select name="category_id" id="category_id" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">{{ __('SKU') }} *</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Brand') }}</label>
                <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Price') }} (EGP) *</label>
                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Sale Price') }} (EGP)</label>
                <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Stock Quantity') }} *</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }} *</label>
                <select name="status" id="status" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                    <option value="archived" {{ old('status', $product->status) === 'archived' ? 'selected' : '' }}>{{ __('Archived') }}</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Short Description') }}</label>
            <textarea name="short_description" id="short_description" rows="2" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">{{ old('short_description', $product->short_description) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Description') }}</label>
            <textarea name="description" id="description" rows="5" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                <span>{{ __('Featured Product') }}</span>
            </label>
        </div>

        {{-- Existing Images --}}
        @if($product->images->isNotEmpty())
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Current Images') }}</label>
                <div class="flex gap-2 flex-wrap">
                    @foreach($product->images as $image)
                        <div class="relative w-20 h-20">
                            <img src="{{ $image->image_url }}" alt="" class="w-full h-full object-cover rounded-lg">
                            <form action="{{ route('admin.products.images.delete', [$product, $image]) }}" method="POST" class="absolute -top-2 -right-2">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">&times;</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Add New Images') }}</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 text-sm">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Update Product') }}</button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition">{{ __('Cancel') }}</a>
        </div>
    </form>
@endsection
