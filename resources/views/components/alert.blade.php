@props(['type' => 'success', 'message' => ''])

<div x-data="{ show: true }" x-show="show" {{ $attributes->merge(['class' => 'px-4 py-3 rounded-lg border flex justify-between items-center']) }}
     :class="{
         'bg-green-50 border-green-200 text-green-700': '{{ $type }}' === 'success',
         'bg-red-50 border-red-200 text-red-700': '{{ $type }}' === 'error',
         'bg-yellow-50 border-yellow-200 text-yellow-700': '{{ $type }}' === 'warning',
         'bg-blue-50 border-blue-200 text-blue-700': '{{ $type }}' === 'info',
     }">
    <span>{{ $message ?? $slot }}</span>
    <button @click="show = false" class="text-current opacity-50 hover:opacity-100">&times;</button>
</div>
