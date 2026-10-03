@extends('layouts.app')

@section('title', __('Dashboard') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ __('My Dashboard') }}</h1>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-gray-500 text-sm">{{ __('Total Orders') }}</p>
                <p class="text-3xl font-bold text-gray-900">{{ $recentOrders->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-gray-500 text-sm">{{ __('Wishlist') }}</p>
                <p class="text-3xl font-bold text-gray-900">{{ $wishlistCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-gray-500 text-sm">{{ __('Addresses') }}</p>
                <p class="text-3xl font-bold text-gray-900">{{ $addressCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-gray-500 text-sm">{{ __('Account Type') }}</p>
                <p class="text-lg font-bold text-primary-600">{{ __('Customer') }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold text-gray-900">{{ __('Recent Orders') }}</h2>
                        <a href="{{ route('dashboard.orders') }}" class="text-sm text-primary-600 hover:text-primary-700">{{ __('View All') }}</a>
                    </div>
                    @forelse($recentOrders as $order)
                        <div class="flex justify-between items-center py-3 border-b last:border-0">
                            <div>
                                <p class="font-medium text-gray-900">#{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $order->status_badge }}">{{ __(ucfirst($order->status)) }}</span>
                                <p class="text-sm font-semibold mt-1">{{ number_format($order->total, 2) }} {{ __('EGP') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">{{ __('No orders yet') }}</p>
                    @endforelse
                </div>
            </div>
            <div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="font-bold text-gray-900 mb-4">{{ __('Quick Links') }}</h2>
                    <div class="space-y-3">
                        <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> {{ __('Edit Profile') }}</a>
                        <a href="{{ route('dashboard.orders') }}" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> {{ __('My Orders') }}</a>
                        <a href="{{ route('dashboard.addresses') }}" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> {{ __('Addresses') }}</a>
                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> {{ __('Wishlist') }}</a>
                        <a href="{{ route('orders.track') }}" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg> {{ __('Track Order') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
