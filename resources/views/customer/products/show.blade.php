@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))
@section('meta_description', $product->meta_description ?? $product->short_description)
@section('meta_keywords', $product->meta_keywords)

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "image": "{{ $product->primaryImage?->image_url }}",
    "description": "{{ $product->short_description }}",
    "sku": "{{ $product->sku }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ $product->brand }}"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "EGP",
        "price": "{{ $product->display_price }}",
        "availability": "{{ $product->is_in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $product->average_rating }}",
        "reviewCount": "{{ $product->approvedReviews->count() }}"
    }
}
</script>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-primary-600">{{ __('Home') }}</a>
            <span>/</span>
            <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-primary-600">{{ $product->category->name }}</a>
            <span>/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
            {{-- Image Gallery --}}
            <div x-data="{ activeImage: '{{ $product->primaryImage?->image_url ?? asset('images/placeholder.jpg') }}' }">
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm mb-4">
                    <img :src="activeImage" alt="{{ $product->name }}" class="w-full aspect-square object-cover">
                </div>
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($product->images as $image)
                            <button @click="activeImage = '{{ $image->image_url }}'" class="rounded-lg overflow-hidden border-2 hover:border-primary-500 transition" :class="{'border-primary-500': activeImage === '{{ $image->image_url }}'}">
                                <img src="{{ $image->image_url }}" alt="{{ $image->alt_text ?? $product->name }}" class="w-full aspect-square object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div>
                <div class="mb-2">
                    <span class="text-sm text-primary-600 font-medium">{{ $product->brand }}</span>
                    <span class="text-gray-300 mx-2">|</span>
                    <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
                </div>

                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                {{-- Rating --}}
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($product->average_rating) ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500">({{ $product->approvedReviews->count() }} {{ __('reviews') }})</span>
                </div>

                {{-- Price --}}
                <div class="flex items-baseline gap-3 mb-6">
                    @if($product->has_discount)
                        <span class="text-3xl font-bold text-primary-600">{{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-xl text-gray-400 line-through">{{ number_format($product->price, 2) }}</span>
                        <span class="bg-red-100 text-red-600 text-sm font-bold px-2 py-1 rounded-full">-{{ $product->discount_percentage }}%</span>
                    @else
                        <span class="text-3xl font-bold text-gray-900">{{ number_format($product->price, 2) }}</span>
                    @endif
                    <span class="text-gray-500">{{ __('EGP') }}</span>
                </div>

                {{-- Short Description --}}
                @if($product->short_description)
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->short_description }}</p>
                @endif

                {{-- Stock Status --}}
                <div class="flex items-center gap-2 mb-6">
                    @if($product->is_in_stock)
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        <span class="text-green-600 font-medium">{{ __('In Stock') }}</span>
                        <span class="text-gray-400">({{ $product->stock }} {{ __('available') }})</span>
                    @else
                        <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                        <span class="text-red-600 font-medium">{{ __('Out of Stock') }}</span>
                    @endif
                </div>

                {{-- Add to Cart --}}
                @if($product->is_in_stock)
                    <form action="{{ route('cart.add') }}" method="POST" class="flex items-center gap-4 mb-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="flex items-center border border-gray-200 rounded-lg">
                            <button type="button" x-data @click="$el.parentElement.querySelector('input[type=number]').stepDown(); $el.parentElement.querySelector('input[type=number]').dispatchEvent(new Event('change'))" class="px-3 py-2 text-gray-500 hover:text-primary-600 transition">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $maxQuantity }}" class="w-16 text-center border-0 focus:ring-0 text-sm">
                            <button type="button" x-data @click="$el.parentElement.querySelector('input[type=number]').stepUp(); $el.parentElement.querySelector('input[type=number]').dispatchEvent(new Event('change'))" class="px-3 py-2 text-gray-500 hover:text-primary-600 transition">+</button>
                        </div>
                        <button type="submit" class="flex-1 bg-primary-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-primary-700 transition shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                            {{ __('Add to Cart') }}
                        </button>
                    </form>
                @endif

                {{-- WhatsApp Order --}}
                <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold transition mb-6">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    {{ __('Order via WhatsApp') }}
                </a>

                {{-- SKU & Social --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <span class="text-sm text-gray-500">{{ __('SKU') }}: {{ $product->sku }}</span>
                    @auth
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                <svg class="w-6 h-6" fill="{{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Full Description --}}
        @if($product->description)
            <div class="mt-12">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Product Description') }}</h2>
                <div class="prose max-w-none text-gray-600 leading-relaxed">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        @endif

        {{-- Reviews --}}
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('Reviews') }} ({{ $product->approvedReviews->count() }})</h2>

            @auth
                <form action="{{ route('reviews.store', $product) }}" method="POST" class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    @csrf
                    <h3 class="font-semibold text-gray-900 mb-4">{{ __('Write a Review') }}</h3>
                    <div class="mb-4" x-data="{ rating: 0 }">
                        <label class="block text-sm text-gray-700 mb-2">{{ __('Rating') }}</label>
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" @click="rating = {{ $i }}" class="focus:outline-none">
                                    <svg class="w-8 h-8 transition" :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" x-model="rating">
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block text-sm text-gray-700 mb-2">{{ __('Your Review') }}</label>
                        <textarea name="comment" id="comment" rows="4" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500" placeholder="{{ __('Share your experience with this product...') }}"></textarea>
                    </div>
                    <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Submit Review') }}</button>
                </form>
            @endauth

            @forelse($product->approvedReviews as $review)
                <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 font-bold">
                            {{ substr($review->customer_name ?? $review->user?->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $review->customer_name ?? $review->user?->name ?? __('Anonymous') }}</p>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 ml-auto">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    @if($review->comment)
                        <p class="text-gray-600">{{ $review->comment }}</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">{{ __('No reviews yet. Be the first to review this product!') }}</p>
            @endforelse
        </div>

        {{-- Related Products --}}
        @if($relatedProducts->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('Related Products') }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
