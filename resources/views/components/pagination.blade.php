@props(['paginator'])

@if ($paginator->hasPages())
    <div class="flex justify-center mt-8">
        <nav class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">{{ __('Previous') }}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white hover:bg-primary-50 rounded-lg border transition">{{ __('Previous') }}</a>
            @endif

            @foreach ($paginator->links()->elements[0] as $page => $url)
                <a href="{{ $url }}" class="px-4 py-2 rounded-lg border transition {{ $page === $paginator->currentPage() ? 'bg-primary-600 text-white border-primary-600' : 'text-gray-600 bg-white hover:bg-primary-50' }}">
                    {{ $page }}
                </a>
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white hover:bg-primary-50 rounded-lg border transition">{{ __('Next') }}</a>
            @else
                <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">{{ __('Next') }}</span>
            @endif
        </nav>
    </div>
@endif
