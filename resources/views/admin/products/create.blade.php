@extends('admin.layouts.admin')

@section('title', __('Add Product'))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Add Product') }}</h1>
        <p class="text-gray-500">{{ __('Add a new product to your catalog') }}</p>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 max-w-4xl">
        @csrf
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Product Name') }} *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 @error('name') border-red-300 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Category') }} *</label>
                <select name="category_id" id="category_id" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    <option value="">{{ __('Select Category') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">{{ __('SKU') }} *</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Brand') }}</label>
                <input type="text" name="brand" id="brand" value="{{ old('brand') }}" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Price') }} (EGP) *</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Sale Price') }} (EGP)</label>
                <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Stock Quantity') }} *</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }} *</label>
                <select name="status" id="status" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Short Description') }}</label>
            <textarea name="short_description" id="short_description" rows="2" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">{{ old('short_description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Description') }}</label>
            <textarea name="description" id="description" rows="5" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                <span>{{ __('Featured Product') }}</span>
            </label>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Product Images') }}</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 text-sm">
            <p class="text-xs text-gray-500 mt-1">{{ __('Max 2MB per image. JPEG, PNG, WebP.') }}</p>
        </div>

        <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Create Product') }}</button>
    </form>
@endsection
