@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-purple-500 text-start text-base font-semibold text-purple-900 bg-purple-100 rounded-r-xl focus:outline-none focus:text-purple-900 focus:bg-purple-100 focus:border-purple-600 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-purple-700 hover:text-purple-800 hover:bg-purple-50 hover:border-purple-300 rounded-r-xl focus:outline-none focus:text-purple-800 focus:bg-purple-50 focus:border-purple-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
