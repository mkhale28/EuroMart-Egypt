@extends('admin.layouts.admin')

@section('title', __('Dashboard'))

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Dashboard') }}</h1>
        <p class="text-gray-500">{{ __('Overview of your store') }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 text-sm">{{ __('Total Revenue') }}</p>
                <span class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalRevenue'], 2) }} {{ __('EGP') }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ __('This month') }}: {{ number_format($stats['revenueThisMonth'], 2) }} {{ __('EGP') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 text-sm">{{ __('Total Orders') }}</p>
                <span class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['totalOrders'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ __('This month') }}: {{ $stats['ordersThisMonth'] }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 text-sm">{{ __('Customers') }}</p>
                <span class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['totalCustomers'] }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 text-sm">{{ __('Products') }}</p>
                <span class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['totalProducts'] }}</p>
            <p class="text-xs text-yellow-600 mt-1">{{ __('Pending') }}: {{ $stats['pendingOrders'] }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8 mb-8">
        {{-- Revenue Chart --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold text-gray-900 mb-4">{{ __('Revenue') }} - {{ now()->year }}</h2>
            <canvas id="revenueChart" height="250"></canvas>
        </div>

        {{-- Top Selling Products --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold text-gray-900 mb-4">{{ __('Top Selling Products') }}</h2>
            <div class="space-y-3">
                @forelse($topProducts as $product)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-sm font-bold text-gray-600">{{ $loop->iteration }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $product->name }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $product->sold_count }} {{ __('sold') }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">{{ __('No sales yet') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-gray-900">{{ __('Recent Orders') }}</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-primary-600 hover:text-primary-700">{{ __('View All') }}</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3 font-medium">{{ __('Order') }}</th>
                        <th class="pb-3 font-medium">{{ __('Customer') }}</th>
                        <th class="pb-3 font-medium">{{ __('Total') }}</th>
                        <th class="pb-3 font-medium">{{ __('Status') }}</th>
                        <th class="pb-3 font-medium">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['recentOrders'] as $order)
                        <tr class="border-b last:border-0">
                            <td class="py-3"><a href="{{ route('admin.orders.show', $order) }}" class="text-primary-600 hover:text-primary-700 font-medium">#{{ $order->order_number }}</a></td>
                            <td class="py-3">{{ $order->customer_name }}</td>
                            <td class="py-3 font-semibold">{{ number_format($order->total, 2) }} {{ __('EGP') }}</td>
                            <td class="py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold {{ $order->status_badge }}">{{ __(ucfirst($order->status)) }}</span></td>
                            <td class="py-3 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const months = @json($revenueByMonth->pluck('month')->map(fn($m) => now()->month($m)->format('M')));
const revenues = @json($revenueByMonth->pluck('revenue'));
new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: '{{ __('Revenue') }}',
            data: revenues,
            borderColor: '#0a5ed4',
            backgroundColor: 'rgba(10, 94, 212, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: value => value.toLocaleString() + ' EGP' } }
        }
    }
});
</script>
@endpush
