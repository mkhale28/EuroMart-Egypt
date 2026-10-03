@props(['product'])

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 group">
    <a href="{{ route('products.show', $product) }}" class="block">
        <div class="relative overflow-hidden rounded-t-xl aspect-square">
            @if($product->primaryImage)
                <img src="{{ $product->primaryImage->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif

            @if($product->has_discount)
                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                    -{{ $product->discount_percentage }}%
                </span>
            @endif

            @if(!$product->is_in_stock)
                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                    <span class="bg-white text-gray-900 px-4 py-2 rounded-full font-bold text-sm">{{ __('Out of Stock') }}</span>
                </div>
            @endif
        </div>

        <div class="p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">{{ $product->brand }}</p>
            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition">{{ $product->name }}</h3>

            <div class="flex items-center gap-1 mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
            </div>

            <div class="flex items-center gap-2">
                @if($product->has_discount)
                    <span class="text-lg font-bold text-primary-600">{{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-sm text-gray-400 line-through">{{ number_format($product->price, 2) }}</span>
                @else
                    <span class="text-lg font-bold text-gray-900">{{ number_format($product->price, 2) }}</span>
                @endif
                <span class="text-xs text-gray-500">{{ __('EGP') }}</span>
            </div>
        </div>
    </a>
</div>
