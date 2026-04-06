@props(['items'])

@php
$activeShift = Auth::check() ? Auth::user()->activeShift : null;
$activeShop = $activeShift ? $activeShift->shop : null;
$bgColor = $activeShop?->getProperty('bg_color', '#8d140c') ?? '#8d140c';
$textColor = $activeShop?->getProperty('text_color', '#f2dec5') ?? '#f2dec5';

// Check if first item is Dashboard - if so, don't render breadcrumb
$showBreadcrumb = count($items) > 0 && $items[0]['label'] !== 'Dashboard';
@endphp

@if($showBreadcrumb)
<nav aria-label="Breadcrumb" class="py-3 px-4 text-white" style="background-color: {{ $bgColor }};">
    <ol class="flex flex-wrap items-center gap-2 text-sm">
        @foreach($items as $index => $item)
            @if($index < count($items) - 1)
                {{-- Clickable breadcrumb item --}}
                <li>
                    <a href="{{ $item['url'] }}" class="hover:opacity-80 transition-opacity" style="color: {{ $textColor }};">
                        {{ $item['label'] }}
                    </a>
                </li>
                <li style="color: {{ $textColor }}; opacity: 0.6;">/</li>
            @else
                {{-- Active (last) breadcrumb item --}}
                <li class="font-semibold" style="color: {{ $textColor }};">
                    {{ $item['label'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif
