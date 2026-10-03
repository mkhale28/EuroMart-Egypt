@extends('admin.layouts.admin')

@section('title', $user->name)

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
        <p class="text-gray-500">{{ $user->email }}</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold text-gray-900 mb-4">{{ __('Customer Info') }}</h2>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">{{ __('Name') }}:</span> {{ $user->name }}</p>
                <p><span class="text-gray-500">{{ __('Email') }}:</span> {{ $user->email }}</p>
                <p><span class="text-gray-500">{{ __('Phone') }}:</span> {{ $user->phone ?? 'N/A' }}</p>
                <p><span class="text-gray-500">{{ __('Joined') }}:</span> {{ $user->created_at->format('M d, Y') }}</p>
                <p>
                    <span class="text-gray-500">{{ __('Status') }}:</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->is_active ? __('Active') : __('Inactive') }}
                    </span>
                </p>
            </div>
            <form action="{{ route('admin.customers.toggle-status', $user) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="text-sm {{ $user->is_active ? 'text-red-600' : 'text-green-600' }} hover:underline">
                    {{ $user->is_active ? __('Deactivate Account') : __('Activate Account') }}
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold text-gray-900 mb-4">{{ __('Recent Orders') }}</h2>
            @forelse($user->orders as $order)
                <div class="flex justify-between items-center py-3 border-b last:border-0">
                    <div>
                        <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-primary-600 hover:text-primary-700">#{{ $order->order_number }}</a>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $order->status_badge }}">{{ __(ucfirst($order->status)) }}</span>
                        <p class="text-sm font-semibold mt-1">{{ number_format($order->total, 2) }} EGP</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">{{ __('No orders yet') }}</p>
            @endforelse
        </div>
    </div>
@endsection
