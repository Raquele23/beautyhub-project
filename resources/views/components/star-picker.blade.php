{{-- resources/views/components/star-picker.blade.php --}}
{{-- Uso: <x-star-picker name="rating" :value="old('rating', 0)" /> --}}
@props(['name' => 'rating', 'value' => 0])

<div x-data="{ rating: {{ (int) $value }}, hover: 0 }" class="flex items-center gap-1">
    <input type="hidden" name="{{ $name }}" :value="rating">

    @for ($i = 1; $i <= 5; $i++)
        <button type="button"
                @click="rating = {{ $i }}"
                @mouseenter="hover = {{ $i }}"
                @mouseleave="hover = 0"
                class="focus:outline-none">
            <svg class="w-8 h-8 transition-colors"
                 :class="(hover || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                 :fill="(hover || rating) >= {{ $i }} ? 'currentColor' : 'none'"
                 stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"
                         stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    @endfor

    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400"
          x-text="['', 'Péssimo', 'Ruim', 'Regular', 'Bom', 'Excelente'][hover || rating] || ''">
    </span>
</div>

@error($name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror