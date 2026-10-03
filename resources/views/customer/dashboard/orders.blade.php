@extends('layouts.app')

@section('title', __('My Orders') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('My Orders') }}</h1>
        </div>

        @forelse($orders as $order)
            <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Order') }} #{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status_badge }}">
                        {{ __(ucfirst($order->status)) }}
                    </span>
                </div>

                <div class="border-t pt-4 flex items-center justify-between">
                    <p class="text-lg font-bold text-gray-900">{{ number_format($order->total, 2) }} {{ __('EGP') }}</p>
                    <a href="{{ route('dashboard.orders.show', $order) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">{{ __('View Details') }}</a>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500 mb-4">{{ __('No orders yet.') }}</p>
                <a href="{{ route('products.index') }}" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Start Shopping') }}</a>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $orders->links('components.pagination') }}
        </div>
    </div>
@endsection
