@extends('admin.layouts.admin')

@section('title', __('Order') . ' #' . $order->order_number)

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Order') }} #{{ $order->order_number }}</h1>
            <p class="text-gray-500">{{ $order->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('Print') }}</a>
            <a href="{{ route('admin.orders.invoice', $order) }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition">{{ __('Download Invoice') }}</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Items --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-bold text-gray-900 mb-4">{{ __('Order Items') }}</h2>
                <div class="divide-y">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 py-3">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-500">{{ __('SKU') }}: {{ $item->product_sku }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $item->quantity }} x {{ number_format($item->price, 2) }}</p>
                                <p class="font-semibold">{{ number_format($item->subtotal, 2) }} {{ __('EGP') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-sm"><span class="text-gray-500">{{ __('Subtotal') }}</span><span>{{ number_format($order->subtotal, 2) }} {{ __('EGP') }}</span></div>
                    @if($order->coupon_discount > 0)
                        <div class="flex justify-between text-sm"><span class="text-gray-500">{{ __('Coupon') }} ({{ $order->coupon_code }})</span><span class="text-green-600">-{{ number_format($order->coupon_discount, 2) }} {{ __('EGP') }}</span></div>
                    @endif
                    <div class="flex justify-between text-sm"><span class="text-gray-500">{{ __('Shipping') }}</span><span>{{ $order->shipping > 0 ? number_format($order->shipping, 2) . ' EGP' : __('Free') }}</span></div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t"><span>{{ __('Total') }}</span><span>{{ number_format($order->total, 2) }} {{ __('EGP') }}</span></div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Status Update --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-bold text-gray-900 mb-4">{{ __('Update Status') }}</h2>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full border-gray-200 rounded-lg mb-3">
                        @foreach(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ __(ucfirst($s)) }}</option>
                        @endforeach
                    </select>
                    <div id="reasonField" x-data="{ show: '{{ $order->status }}' === 'cancelled' }">
                        <textarea name="cancellation_reason" placeholder="{{ __('Cancellation reason...') }}" rows="2" class="w-full border-gray-200 rounded-lg mb-3 text-sm" x-show="document.querySelector('[name=status]').value === 'cancelled'"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-primary-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Update') }}</button>
                </form>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-bold text-gray-900 mb-4">{{ __('Customer Information') }}</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-500">{{ __('Name') }}:</span> {{ $order->customer_name }}</p>
                    <p><span class="text-gray-500">{{ __('Phone') }}:</span> {{ $order->customer_phone }}</p>
                    @if($order->customer_email)<p><span class="text-gray-500">{{ __('Email') }}:</span> {{ $order->customer_email }}</p>@endif
                    <p><span class="text-gray-500">{{ __('Governorate') }}:</span> {{ $order->governorate }}</p>
                    <p><span class="text-gray-500">{{ __('Address') }}:</span> {{ $order->address }}</p>
                    @if($order->notes)<p><span class="text-gray-500">{{ __('Notes') }}:</span> {{ $order->notes }}</p>@endif
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-bold text-gray-900 mb-4">{{ __('Payment') }}</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-500">{{ __('Method') }}:</span> {{ __(str_replace('_', ' ', ucfirst($order->payment_method))) }}</p>
                    <p><span class="text-gray-500">{{ __('Status') }}:</span> <span class="capitalize">{{ $order->payment_status }}</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection
