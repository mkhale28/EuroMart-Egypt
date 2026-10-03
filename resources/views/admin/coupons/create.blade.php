@extends('admin.layouts.admin')

@section('title', __('Add Coupon'))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Add Coupon') }}</h1>
    </div>
    <form action="{{ route('admin.coupons.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
        @csrf
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Coupon Code') }} *</label>
                <input type="text" name="code" value="{{ old('code') }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 uppercase">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Type') }} *</label>
                <select name="type" required class="w-full border-gray-200 rounded-lg">
                    <option value="fixed">{{ __('Fixed') }}</option>
                    <option value="percentage">{{ __('Percentage') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Value') }} *</label>
                <input type="number" name="value" value="{{ old('value') }}" step="0.01" min="0" required class="w-full border-gray-200 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Min Order Amount') }}</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount') }}" step="0.01" min="0" class="w-full border-gray-200 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Max Uses') }}</label>
                <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="1" class="w-full border-gray-200 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Max Discount') }}</label>
                <input type="number" name="max_discount" value="{{ old('max_discount') }}" step="0.01" min="0" class="w-full border-gray-200 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }}</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="w-full border-gray-200 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Expiry Date') }}</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full border-gray-200 rounded-lg">
            </div>
        </div>
        <div class="mb-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-primary-600">
                <span>{{ __('Active') }}</span>
            </label>
        </div>
        <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Create Coupon') }}</button>
    </form>
@endsection
