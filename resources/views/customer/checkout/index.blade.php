@extends('layouts.app')

@section('title', __('Checkout') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">{{ __('Checkout') }}</h1>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <form action="{{ route('checkout.process') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
                    @csrf
                    <h2 class="font-bold text-gray-900 text-lg mb-6">{{ __('Shipping Information') }}</h2>

                    <div class="grid sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }} *</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', auth()->user()?->name) }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('full_name') border-red-300 @enderror">
                            @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }} *</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', auth()->user()?->phone) }}" required placeholder="01012345678" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('phone') border-red-300 @enderror">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="governorate" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Governorate') }} *</label>
                        <select name="governorate" id="governorate" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('governorate') border-red-300 @enderror">
                            <option value="">{{ __('Select Governorate') }}</option>
                            @foreach(['Cairo', 'Alexandria', 'Giza', 'Port Said', 'Suez', 'Luxor', 'Mansoura', 'Tanta', 'Asyut', 'Ismailia', 'Faiyum', 'Zagazig', 'Damietta', 'Aswan', 'Minya', 'Qena', 'Sohag', 'Beni Suef', 'Sharqia', 'Dakahlia', 'Beheira', 'Monufia', 'Qalyubia', 'Kafr El Sheikh', 'Gharbia', 'Matrouh', 'Red Sea', 'New Valley', 'North Sinai', 'South Sinai'] as $gov)
                                <option value="{{ $gov }}" {{ old('governorate') === $gov ? 'selected' : '' }}>{{ __($gov) }}</option>
                            @endforeach
                        </select>
                        @error('governorate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Address') }} *</label>
                        <textarea name="address" id="address" rows="3" required placeholder="{{ __('Street, building, apartment number...') }}" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('address') border-red-300 @enderror">{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Order Notes') }}</label>
                        <textarea name="notes" id="notes" rows="2" placeholder="{{ __('Special instructions, delivery time, etc.') }}" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">{{ old('notes') }}</textarea>
                    </div>

                    <h2 class="font-bold text-gray-900 text-lg mb-4">{{ __('Payment Method') }}</h2>
                    <div class="space-y-3 mb-6">
                        <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-primary-300 transition has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                            <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }} class="text-primary-600 focus:ring-primary-500" required>
                            <div>
                                <span class="font-semibold text-gray-900">{{ __('Cash on Delivery') }}</span>
                                <p class="text-sm text-gray-500">{{ __('Pay when you receive your order') }}</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-primary-300 transition has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                            <input type="radio" name="payment_method" value="vodafone_cash" {{ old('payment_method') === 'vodafone_cash' ? 'checked' : '' }} class="text-primary-600 focus:ring-primary-500">
                            <div>
                                <span class="font-semibold text-gray-900">{{ __('Vodafone Cash') }}</span>
                                <p class="text-sm text-gray-500">{{ __('Pay via Vodafone Cash wallet') }}</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-primary-300 transition has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                            <input type="radio" name="payment_method" value="instapay" {{ old('payment_method') === 'instapay' ? 'checked' : '' }} class="text-primary-600 focus:ring-primary-500">
                            <div>
                                <span class="font-semibold text-gray-900">{{ __('Instapay') }}</span>
                                <p class="text-sm text-gray-500">{{ __('Pay via Instapay transfer') }}</p>
                            </div>
                        </label>
                    </div>

                    {{-- Coupon --}}
                    <div class="border-t pt-6" x-data="{ code: '', discount: 0, message: '', valid: false, loading: false }">
                        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Have a coupon?') }}</h3>
                        <div class="flex gap-2">
                            <input type="text" name="coupon_code" x-model="code" placeholder="{{ __('Enter coupon code') }}" class="flex-1 border-gray-200 rounded-lg focus:ring-primary-500 text-sm">
                            <button type="button" @click="if(code) { loading = true; fetch('{{ route('checkout.validate-coupon') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ code: code }) }).then(r => r.json()).then(d => { valid = d.valid; discount = d.discount; message = d.message; loading = false; }) }" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700 transition" x-text="loading ? '...' : '{{ __('Apply') }}'"></button>
                        </div>
                        <template x-if="message">
                            <p class="text-sm mt-2" :class="valid ? 'text-green-600' : 'text-red-600'" x-text="message"></p>
                        </template>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-primary-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-primary-700 transition text-lg">
                        {{ __('Place Order') }}
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 h-fit sticky top-24">
                <h3 class="font-bold text-gray-900 text-lg mb-4">{{ __('Order Summary') }}</h3>
                <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                    @foreach($cartItems as $item)
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                @if($item->product->primaryImage)
                                    <img src="{{ $item->product->primaryImage->image_url }}" alt="" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                            </div>
                            <span class="text-sm font-semibold">{{ number_format($item->subtotal, 2) }} {{ __('EGP') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t pt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('Subtotal') }}</span>
                        <span class="font-semibold">{{ number_format($subtotal, 2) }} {{ __('EGP') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('Shipping') }}</span>
                        <span class="text-green-600 font-semibold">{{ __('Free') }}</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between">
                        <span class="font-bold">{{ __('Total') }}</span>
                        <span class="font-bold text-lg">{{ number_format($subtotal, 2) }} {{ __('EGP') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
