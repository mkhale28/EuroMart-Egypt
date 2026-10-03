@extends('admin.layouts.admin')

@section('title', __('Categories'))

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Categories') }}</h1>
            <p class="text-gray-500">{{ __('Manage product categories') }}</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition font-medium">{{ __('Add Category') }}</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Name') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Products') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Order') }}</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($categories as $cat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $cat->name }}</td>
                        <td class="px-4 py-3">{{ $cat->products_count }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $cat->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $cat->sort_order }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="text-primary-600 hover:text-primary-700">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">{{ $categories->links() }}</div>
    </div>
@endsection
