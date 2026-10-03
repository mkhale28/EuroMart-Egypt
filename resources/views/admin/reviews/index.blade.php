@extends('admin.layouts.admin')

@section('title', __('Reviews'))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Reviews') }}</h1>
        <p class="text-gray-500">{{ __('Manage customer reviews') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <div class="flex gap-2">
                <a href="{{ route('admin.reviews.index') }}" class="px-3 py-1.5 rounded-lg text-sm {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">{{ __('All') }}</a>
                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg text-sm {{ request('status') === 'pending' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">{{ __('Pending') }}</a>
                <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-3 py-1.5 rounded-lg text-sm {{ request('status') === 'approved' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">{{ __('Approved') }}</a>
            </div>
        </div>
        @foreach($reviews as $review)
            <div class="p-4 border-b last:border-0">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-900">{{ $review->customer_name }}</span>
                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        @if($review->comment)<p class="text-gray-600 text-sm">{{ $review->comment }}</p>@endif
                        <p class="text-xs text-gray-400 mt-1">{{ __('Product') }}: <a href="{{ route('admin.products.edit', $review->product) }}" class="text-primary-600">{{ $review->product->name }}</a></p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 ml-4">
                        @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-semibold hover:bg-green-200 transition">{{ __('Approve') }}</button>
                            </form>
                        @else
                            <form action="{{ route('admin.reviews.hide', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-semibold hover:bg-yellow-200 transition">{{ __('Hide') }}</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 text-xs font-semibold">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="p-4 border-t">{{ $reviews->links() }}</div>
    </div>
@endsection
