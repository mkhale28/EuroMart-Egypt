@extends('layouts.app')

@section('title', __('Order Confirmed') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Order Placed Successfully!') }}</h1>
        <p class="text-gray-500 mb-6">{{ __('Thank you for your order. We will process it shortly.') }}</p>
        <div class="bg-gray-50 rounded-xl p-6 mb-8 inline-block">
            <p class="text-sm text-gray-500 mb-1">{{ __('Order Number') }}</p>
            <p class="text-2xl font-bold text-primary-600">{{ $order->order_number }}</p>
        </div>
        <div class="space-y-4">
            <a href="{{ route('orders.track') }}" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-primary-700 transition">{{ __('Track Your Order') }}</a>
            <br>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold">{{ __('Continue Shopping') }}</a>
        </div>
    </div>
@endsection
