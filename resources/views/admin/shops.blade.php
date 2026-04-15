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
            {{ __('admin.shop_settings') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12" style="background-color: #242f6d; min-height: 450px;">
        <div class="max-w-6xl mx-auto px-2 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg text-sm flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2 mb-2">
                    <i class="fas fa-store text-indigo-300"></i>
                    Shop Configuration
                </h2>
                <p class="text-gray-300 text-sm">Manage your shop settings, branding, and colors</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($shops as $shop)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        {{-- Header with border color --}}
                        <div class="h-1" style="background-color: {{ $shop->getProperty('primary_color', '#4f46e5') }}"></div>
                        
                        <div class="p-6">
                            {{-- Shop Title --}}
                            <div class="flex items-start justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fas {{ $shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i>
                                    {{ $shop->name }}
                                </h3>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.shops.preview', $shop) }}" class="text-sm font-semibold text-green-600 hover:text-green-700 px-3 py-1.5 rounded hover:bg-green-50 transition-colors inline-flex items-center gap-1" target="_blank" title="Preview storefront">
                                        <i class="fas fa-eye"></i> Preview
                                    </a>
                                    <a href="{{ route('admin.shops.edit', $shop) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 px-3 py-1.5 rounded hover:bg-indigo-50 transition-colors inline-flex items-center gap-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </div>
                            </div>

                            {{-- Logo Preview --}}
                            @if($shop->getProperty('logo_path'))
                                <div class="mb-6 p-4 rounded-lg border-2 border-gray-200 flex items-center justify-center" style="background-color: {{ $shop->getProperty('bg_color', '#ffffff') }}; min-height: 100px;">
                                    <img src="{{ $shop->getProperty('logo_path') }}" alt="{{ $shop->name }} logo" class="h-20 max-w-[140px] object-contain">
                                </div>
                            @else
                                <div class="mb-6 p-4 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50 text-gray-400 text-sm min-height: 100px;">
                                    <i class="fas fa-image text-3xl opacity-30"></i>
                                </div>
                            @endif

                            {{-- Color Settings --}}
                            <div class="space-y-4">
                                {{-- Background Color --}}
                                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-fill-drip text-indigo-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 font-medium">{{ __('admin.background_color') }}</p>
                                            <code class="text-xs bg-gray-100 px-2 py-1 rounded mt-1 block">{{ $shop->getProperty('bg_color', '#ffffff') }}</code>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 rounded-lg border-2 border-gray-300 flex-shrink-0" style="background-color: {{ $shop->getProperty('bg_color', '#ffffff') }}"></div>
                                </div>

                                {{-- Text Color --}}
                                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-type text-blue-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 font-medium">{{ __('admin.text_color') }}</p>
                                            <code class="text-xs bg-gray-100 px-2 py-1 rounded mt-1 block">{{ $shop->getProperty('text_color', '#1f2937') }}</code>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 rounded-lg border-2 border-gray-300 flex-shrink-0" style="background-color: {{ $shop->getProperty('text_color', '#1f2937') }}"></div>
                                </div>

                                {{-- Primary Color --}}
                                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-palette text-purple-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 font-medium">Primary Color</p>
                                            <code class="text-xs bg-gray-100 px-2 py-1 rounded mt-1 block">{{ $shop->getProperty('primary_color', '#4f46e5') }}</code>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 rounded-lg border-2 border-gray-300 flex-shrink-0" style="background-color: {{ $shop->getProperty('primary_color', '#4f46e5') }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
