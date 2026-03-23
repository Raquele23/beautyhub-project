@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-purple-800 focus:outline-none transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-purple-800 hover:text-purple-600 hover:border-purple-300 focus:outline-none focus:text-purple-700 focus:border-purple-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($active ?? false) style="border-color: #A675D6;" @endif>
    {{ $slot }}
</a>
