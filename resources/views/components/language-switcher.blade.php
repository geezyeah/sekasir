@php
    $currentLocale = app()->getLocale();
    $languages = [
        'en' => ['name' => 'English', 'flag' => '🇬🇧'],
        'id' => ['name' => 'Bahasa Indonesia', 'flag' => '🇮🇩'],
    ];
@endphp

<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <button class="inline-flex items-center px-2 sm:px-3 py-2 border border-transparent text-xs sm:text-sm leading-4 font-medium rounded-md hover:opacity-75 focus:outline-none transition ease-in-out duration-150"
                style="@if(Auth::user()->isAdmin() || request()->routeIs('shifts.select')) color: white; background-color: transparent; @elseif($activeShop ?? false) color: {{ $activeShop->getProperty('text_color', '#1f2937') }}; background-color: transparent; @else color: white; background-color: rgba(255, 255, 255, 0.2); @endif">
            <i class="fas fa-globe mr-1"></i>
            <span class="hidden lg:inline text-xs">{{ strtoupper($currentLocale) }}</span>
            <div class="ms-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </button>
    </x-slot>

    <x-slot name="content">
        @foreach($languages as $locale => $lang)
            <a href="{{ route('language.switch', $locale) }}" 
               class="block w-full text-left px-4 py-2 text-sm leading-5 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150 flex items-center gap-2 {{ $currentLocale === $locale ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700' }}">
                <span class="text-lg">{{ $lang['flag'] }}</span>
                <div>
                    <div class="font-medium">{{ $lang['name'] }}</div>
                    <div class="text-xs opacity-60">{{ strtoupper($locale) }}</div>
                </div>
                @if($currentLocale === $locale)
                    <i class="fas fa-check ml-auto text-indigo-600"></i>
                @endif
            </a>
        @endforeach
    </x-slot>
</x-dropdown>
