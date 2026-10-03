@extends('admin.layouts.admin')

@section('title', __('Add Category'))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Add Category') }}</h1>
    </div>
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }} *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
            <textarea name="description" rows="3" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">{{ old('description') }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Sort Order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-24 border-gray-200 rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Image') }}</label>
            <input type="file" name="image" accept="image/*" class="w-full border-gray-200 rounded-lg text-sm">
        </div>
        <div class="mb-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-primary-600">
                <span>{{ __('Active') }}</span>
            </label>
        </div>
        <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Create Category') }}</button>
    </form>
@endsection
