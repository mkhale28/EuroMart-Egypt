@extends('admin.layouts.admin')

@section('title', __('Customers'))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Customers') }}</h1>
        <p class="text-gray-500">{{ __('Manage customer accounts') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <form method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search customers...') }}" class="border-gray-200 rounded-lg text-sm">
                <button type="submit" class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium hover:bg-gray-200 transition ml-2">{{ __('Search') }}</button>
            </form>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Name') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Email') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Phone') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Orders') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Joined') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $customer->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $customer->email }}</td>
                        <td class="px-4 py-3">{{ $customer->phone }}</td>
                        <td class="px-4 py-3">{{ $customer->orders_count }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $customer->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $customer->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('View') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">{{ $customers->links() }}</div>
    </div>
@endsection
