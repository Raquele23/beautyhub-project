{{-- resources/views/components/star-rating.blade.php --}}
{{-- Uso: <x-star-rating :rating="4" /> --}}
@props(['rating' => 0, 'size' => 'md'])

@php
    $sz = ['sm' => 'w-4 h-4', 'md' => 'w-5 h-5', 'lg' => 'w-6 h-6'][$size] ?? 'w-5 h-5';
@endphp

<div class="flex items-center gap-0.5">
    @for ($i = 1; $i <= 5; $i++)
        <svg class="{{ $sz }} {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"
             fill="{{ $rating >= $i ? 'currentColor' : 'none' }}"
             stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                     stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    @endfor
</div>