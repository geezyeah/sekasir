<x-app-layout>
    <style>
        body {
            background-color: #242f6d;
        }
        header {
            background-color: #242f6d !important;
        }
        nav {
            background-color: #242f6d !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">
            Select Shop
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12" style="background-color: #242f6d;">
        <div class="max-w-full lg:max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="text-center mb-8 sm:mb-12">
                <h1 class="text-2xl sm:text-4xl font-bold text-white mb-2">Welcome, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-sm sm:text-lg text-gray-200">Select a shop to start your shift and begin work</p>
            </div>

            <!-- Shop Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                @foreach($shops as $shop)
                    @php
                        $bgColor = $shop->getProperty('bg_color', '#8d140c');
                        $textColor = $shop->getProperty('text_color', '#f2dec5');
                    @endphp
                    <form method="POST" action="{{ route('shifts.start') }}" class="group">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                        
                        <div class="bg-white overflow-hidden shadow-md rounded-2xl hover:shadow-2xl transition-all duration-300 h-full border-t-4 p-6 sm:p-8" style="border-color: {{ $bgColor }};">
                            <!-- Shop Logo -->
                            <div class="mb-4 text-center">
                                @php
                                    $logoPath = $shop->getProperty('logo_path', null);
                                @endphp
                                @if($logoPath)
                                    <img src="{{ $logoPath }}" alt="{{ $shop->name }}" style="max-width: 120px; height: auto; margin: 0 auto; min-height:75px;" class="object-contain">
                                @else
                                    <div class="text-5xl sm:text-6xl transform group-hover:scale-110 transition-transform duration-300">
                                        @if($shop->name === 'Ice Lepen')
                                            🍦
                                        @else
                                            🥟
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Shop Name & Description -->
                            <div class="text-center mb-6">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">{{ $shop->name }}</h3>
                                <p class="text-sm text-gray-500">
                                    @if($shop->name === 'Ice Lepen')
                                        Ice Cream Shop
                                    @else
                                        Dimsum Shop
                                    @endif
                                </p>
                            </div>

                            <!-- Shop Status -->
                            <div class="mb-6 p-3 rounded-lg border text-center" style="border-color: {{ $bgColor }}; background-color: rgba(255, 255, 255, 0.5);">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="inline-block w-2 h-2 rounded-full" style="background-color: {{ $bgColor }};"></span>
                                    <span class="text-xs sm:text-sm font-medium" style="color: {{ $bgColor }};">{{ __('pos.active') }}</span>
                                </div>
                            </div>

                            <!-- Start Shift Button -->
                            <button type="submit" class="w-full px-4 py-2.5 sm:py-3 rounded-lg font-semibold text-white text-sm sm:text-base transition-all duration-300 flex items-center justify-center gap-2 hover:shadow-lg" style="background-color: {{ $bgColor }};">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Start Shift
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>

            <!-- Additional Info -->
            <div class="mt-12 sm:mt-16 text-center">
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200 inline-block">
                    <p class="text-xs sm:text-sm text-gray-600">
                        <svg class="w-4 h-4 inline mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                        </svg>
                        Kenapa kau kerjakan sekarang, jika kau bisa mengerjakannya besok.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
