@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3 py-2 border-b-2 border-purple-500 text-sm font-semibold leading-5 text-purple-900 bg-purple-100 rounded-lg focus:outline-none transition duration-150 ease-in-out'
    : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-purple-800 rounded-lg hover:text-purple-700 hover:bg-purple-50 hover:border-purple-300 focus:outline-none focus:text-purple-700 focus:bg-purple-50 focus:border-purple-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
