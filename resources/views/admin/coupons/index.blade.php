@extends('admin.layouts.admin')

@section('title', __('Coupons'))

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Coupons') }}</h1>
            <p class="text-gray-500">{{ __('Manage discount coupons') }}</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition font-medium">{{ __('Add Coupon') }}</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Code') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Type') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Value') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Uses') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Expires') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($coupons as $coupon)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono font-bold text-gray-900">{{ $coupon->code }}</td>
                        <td class="px-4 py-3">{{ $coupon->type === 'fixed' ? __('Fixed') : __('Percentage') }}</td>
                        <td class="px-4 py-3">{{ $coupon->type === 'fixed' ? number_format($coupon->value, 2) . ' EGP' : $coupon->value . '%' }}</td>
                        <td class="px-4 py-3">{{ $coupon->used_count }}/{{ $coupon->max_uses ?? '∞' }}</td>
                        <td class="px-4 py-3">{{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : '∞' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $coupon->isValid() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $coupon->isValid() ? __('Active') : __('Expired') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-primary-600 hover:text-primary-700">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">{{ $coupons->links() }}</div>
    </div>
@endsection
