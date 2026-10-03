@extends('layouts.app')

@section('title', __('Shopping Cart') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">{{ __('Shopping Cart') }}</h1>

        @if($cartItems->isEmpty())
            <div class="text-center py-20">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ __('Your cart is empty') }}</h2>
                <p class="text-gray-500 mb-6">{{ __('Start shopping to add items to your cart') }}</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Continue Shopping') }}</a>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4">
                            <a href="{{ route('products.show', $item->product) }}" class="w-20 h-20 shrink-0">
                                @if($item->product->primaryImage)
                                    <img src="{{ $item->product->primaryImage->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <div class="w-full h-full bg-gray-200 rounded-lg"></div>
                                @endif
                            </a>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('products.show', $item->product) }}" class="font-semibold text-gray-900 hover:text-primary-600 transition line-clamp-1">{{ $item->product->name }}</a>
                                <p class="text-sm text-gray-500">{{ $item->product->brand }}</p>
                                <p class="text-primary-600 font-bold mt-1">{{ number_format($item->product->display_price, 2) }} {{ __('EGP') }}</p>
                            </div>
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center border border-gray-200 rounded-lg">
                                    <button type="button" onclick="this.parentElement.querySelector('input[type=number]').stepDown(); this.parentElement.querySelector('input[type=number]').dispatchEvent(new Event('change'))" class="px-2 py-1 text-gray-500 hover:text-primary-600">-</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" onchange="this.form.submit()" class="w-12 text-center border-0 focus:ring-0 text-sm">
                                    <button type="button" onclick="this.parentElement.querySelector('input[type=number]').stepUp(); this.parentElement.querySelector('input[type=number]').dispatchEvent(new Event('change'))" class="px-2 py-1 text-gray-500 hover:text-primary-600">+</button>
                                </div>
                            </form>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">{{ number_format($item->subtotal, 2) }} {{ __('EGP') }}</p>
                            </div>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-600 transition">{{ __('Clear Cart') }}</button>
                    </form>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 h-fit sticky top-24">
                    <h3 class="font-bold text-gray-900 text-lg mb-4">{{ __('Order Summary') }}</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('Subtotal') }}</span>
                            <span class="font-semibold">{{ number_format($subtotal, 2) }} {{ __('EGP') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('Shipping') }}</span>
                            <span class="text-green-600 font-semibold">{{ __('Calculated at checkout') }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="font-bold">{{ __('Total') }}</span>
                            <span class="font-bold text-lg">{{ number_format($subtotal, 2) }} {{ __('EGP') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="block text-center mt-6 bg-primary-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-primary-700 transition">{{ __('Proceed to Checkout') }}</a>
                    <a href="{{ route('products.index') }}" class="block text-center mt-3 text-primary-600 hover:text-primary-700 text-sm font-medium">{{ __('Continue Shopping') }}</a>
                </div>
            </div>
        @endif
    </div>
@endsection
