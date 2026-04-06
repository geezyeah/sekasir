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

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.shops'), 'label' => 'Shops']
    ]" />

    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">
            Shop Settings
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12" style="background-color: #242f6d; min-height: 450px;">
        <div class="max-w-4xl mx-auto px-2 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-2 sm:mb-4 bg-green-50 border border-green-200 rounded-lg p-2 sm:p-4">
                    <p class="text-xs sm:text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 lg:gap-6">
                @foreach($shops as $shop)
                    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-6 border-l-4" style="border-color: {{ $shop->getProperty('primary_color', '#4f46e5') }}">
                        <div class="flex items-start justify-between mb-2 sm:mb-4">
                            <div>
                                <h3 class="text-sm sm:text-lg font-bold"><i class="fas {{ $shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }}; margin-right: 8px;"></i>{{ $shop->name }}</h3>
                            </div>
                            <a href="{{ route('admin.shops.edit', $shop) }}" class="text-xs sm:text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                                Edit →
                            </a>
                        </div>

                        {{-- Logo Preview --}}
                        @if($shop->getProperty('logo_path'))
                            <div class="mb-2 sm:mb-4 p-2 sm:p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center" style="background-color: {{ $shop->getProperty('bg_color', '#ffffff') }}; min-height: 60px sm:min-height-80px;">
                                <img src="{{ $shop->getProperty('logo_path') }}" alt="{{ $shop->name }} logo" class="h-12 sm:h-16 max-w-[100px] sm:max-w-[120px] object-contain">
                            </div>
                        @endif

                        <div class="space-y-1.5 sm:space-y-3">
                            <div class="flex items-center gap-1.5 sm:gap-3">
                                <div class="text-xs sm:text-sm font-medium text-gray-600 min-w-fit">Background:</div>
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded border border-gray-300" style="background-color: {{ $shop->getProperty('bg_color', '#ffffff') }}"></div>
                                    <code class="text-xs bg-gray-100 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded line-clamp-1">{{ $shop->getProperty('bg_color', '#ffffff') }}</code>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 sm:gap-3">
                                <div class="text-xs sm:text-sm font-medium text-gray-600 min-w-fit">Text:</div>
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded border border-gray-300" style="background-color: {{ $shop->getProperty('text_color', '#1f2937') }}"></div>
                                    <code class="text-xs bg-gray-100 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded line-clamp-1">{{ $shop->getProperty('text_color', '#1f2937') }}</code>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 sm:gap-3">
                                <div class="text-xs sm:text-sm font-medium text-gray-600 min-w-fit">Primary:</div>
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded border border-gray-300" style="background-color: {{ $shop->getProperty('primary_color', '#4f46e5') }}"></div>
                                    <code class="text-xs bg-gray-100 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded line-clamp-1">{{ $shop->getProperty('primary_color', '#4f46e5') }}</code>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
