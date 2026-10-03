@extends('admin.layouts.admin')

@section('title', __('Orders'))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Orders') }}</h1>
        <p class="text-gray-500">{{ __('Manage customer orders') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <form method="GET" class="flex flex-wrap gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search orders...') }}" class="border-gray-200 rounded-lg text-sm">
                <select name="status" class="border-gray-200 rounded-lg text-sm">
                    <option value="">{{ __('All Status') }}</option>
                    @foreach(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ __(ucfirst($s)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium hover:bg-gray-200 transition">{{ __('Filter') }}</button>
            </form>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Order') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Customer') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Total') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Payment') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Date') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">#{{ $order->order_number }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->customer_phone }}</p>
                        </td>
                        <td class="px-4 py-3 font-semibold">{{ number_format($order->total, 2) }} {{ __('EGP') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ __(str_replace('_', ' ', ucfirst($order->payment_method))) }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold {{ $order->status_badge }}">{{ __(ucfirst($order->status)) }}</span></td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('View') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">{{ $orders->links() }}</div>
    </div>
@endsection
