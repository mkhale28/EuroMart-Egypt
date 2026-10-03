@extends('layouts.app')

@section('title', __('Track Order') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8 text-center">{{ __('Track Your Order') }}</h1>

        <form method="POST" action="{{ route('orders.track.post') }}" class="bg-white rounded-xl shadow-sm p-6 mb-8">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="order_number" value="{{ old('order_number', $order->order_number ?? '') }}" placeholder="{{ __('Enter order number (e.g., EM-20240101-0001)') }}" class="flex-1 border-gray-200 rounded-lg focus:ring-primary-500" required>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Track') }}</button>
            </div>
        </form>

        @if(isset($order))
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-gray-900">{{ __('Order') }} #{{ $order->order_number }}</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $order->status_badge }}">{{ __(ucfirst($order->status)) }}</span>
                </div>

                <div class="relative">
                    @php
                        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                        $currentIndex = array_search($order->status, $statuses);
                        if ($currentIndex === false) $currentIndex = -1;
                    @endphp
                    <div class="flex justify-between mb-2">
                        @foreach(['pending' => __('Order Placed'), 'confirmed' => __('Confirmed'), 'processing' => __('Processing'), 'shipped' => __('Shipped'), 'delivered' => __('Delivered')] as $key => $label)
                            <div class="text-center">
                                <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-sm font-bold mb-1 
                                    {{ array_search($key, $statuses) <= $currentIndex ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                    {{ array_search($key, $statuses) + 1 }}
                                </div>
                                <span class="text-xs {{ array_search($key, $statuses) <= $currentIndex ? 'text-primary-600 font-medium' : 'text-gray-400' }}">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200 -z-10">
                        <div class="h-full bg-primary-600 transition-all" style="width: {{ ($currentIndex + 1) * 25 }}%"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
