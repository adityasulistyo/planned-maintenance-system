<!-- <a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out']) }}>{{ $slot }}</a> -->
@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block px-4 py-2 text-sm text-blue-600 bg-gray-100'
            : 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
