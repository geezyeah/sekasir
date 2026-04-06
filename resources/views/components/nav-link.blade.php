@props(['active'])

@php
$activeStyles = 'border-b-2 border-yellow-200 text-white';
$inactiveStyles = 'border-b-2 border-transparent text-gray-100 hover:text-white hover:border-yellow-200';
$baseClasses = 'inline-flex items-center px-0.5 pt-1 text-xs lg:text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out whitespace-nowrap';
@endphp

<a {{ $attributes->merge(['class' => $baseClasses . ' ' . (($active ?? false) ? $activeStyles : $inactiveStyles)]) }}>
    {{ $slot }}
</a>
