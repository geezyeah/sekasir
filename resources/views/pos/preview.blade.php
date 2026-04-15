<x-app-layout>
    <style>
        body {
            background-color: {{ $shop->getProperty('bg_color', '#8d140c') }};
        }
        header {
            background-color: {{ $shop->getProperty('bg_color', '#8d140c') }} !important;
        }
        nav {
            background-color: {{ $shop->getProperty('bg_color', '#8d140c') }} !important;
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg text-white leading-tight">{{ $shop->name }} - Storefront Preview (Read-Only)</h2>
            <a href="{{ route('admin.shops') }}" class="text-white hover:text-gray-200 transition-colors">← Back to Shops</a>
        </div>
    </x-slot>

    <div class="py-6" style="background-color: {{ $shop->getProperty('bg_color', '#8d140c') }};">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <!-- Header Info -->
            <div class="mb-6">
                <h1 class="text-4xl font-bold mb-2" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">{{ $shop->name }}</h1>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 rounded-full" style="background-color: #10b981;"></span>
                    <span class="text-sm font-semibold" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Preview Mode (Read-Only)</span>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6 border-l-4" style="border-color: {{ $shop->getProperty('bg_color', '#8d140c') }};">
                <p class="text-gray-700 font-semibold mb-2">📋 Storefront Preview</p>
                <p class="text-sm text-gray-600">This is how products appear to workers. You can see all product names, types, and prices. No interactions are possible in this preview mode.</p>
            </div>

            <!-- Product Grid -->
            <div class="rounded-2xl shadow-lg p-6" style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                <h3 class="text-2xl font-bold mb-6" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Products</h3>
                
                @if($products->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-lg" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }}; opacity: 0.6;">No active products</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($products as $product)
                            <div class="flex flex-col items-center p-4 rounded-xl border-2 cursor-not-allowed opacity-75 hover:opacity-100 transition-opacity"
                                 style="background-color: {{ $shop->getProperty('text_color', '#F5E6D3') }}; border-color: rgba(255,255,255,0.3);">
                                
                                <!-- Icon -->
                                <div class="text-3xl mb-2 flex items-center justify-center">
                                    @php
                                        $typeIcon = 'fa-ice-cream';
                                        $typeColor = '#c41e3a';
                                        
                                        if($product->productType) {
                                            $typeName = strtolower($product->productType->name);
                                            
                                            if(str_contains($typeName, 'cone')) {
                                                $typeIcon = 'fa-ice-cream';
                                                $typeColor = '#c41e3a';
                                            } elseif(str_contains($typeName, 'cup')) {
                                                $typeIcon = 'fa-whiskey-glass';
                                                $typeColor = '#8b5a3c';
                                            } elseif(str_contains($typeName, 'bowl')) {
                                                $typeIcon = 'fa-bowl-food';
                                                $typeColor = '#f39c12';
                                            } elseif(str_contains($typeName, 'box')) {
                                                $typeIcon = 'fa-box';
                                                $typeColor = '#9b59b6';
                                            } elseif(str_contains($typeName, 'package')) {
                                                $typeIcon = 'fa-box-open';
                                                $typeColor = '#e74c3c';
                                            }
                                        }
                                    @endphp
                                    <i class="fas {{ $typeIcon }}" style="color: {{ $typeColor }};"></i>
                                </div>

                                <!-- Product Name -->
                                <span class="text-sm font-semibold text-center leading-tight line-clamp-2" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                                    {{ $product->name }}
                                </span>

                                <!-- Product Type -->
                                @if($product->productType)
                                    <span class="text-xs font-medium text-center mt-1" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; opacity: 0.7;">
                                        {{ strtoupper($product->productType->name) }}
                                    </span>
                                @endif

                                <!-- Price -->
                                <span class="text-sm font-bold mt-2" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>

                                <!-- Seasonal Badge -->
                                @if($product->is_seasonal)
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full mt-2">Seasonal</span>
                                @endif

                                <!-- Disabled Badge -->
                                <span class="text-xs bg-gray-300 text-gray-700 px-2 py-1 rounded mt-2 font-medium">
                                    🔒 Preview
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Summary -->
            <div class="mt-6 bg-white rounded-lg shadow-md p-4">
                <p class="text-gray-700 font-semibold mb-2">📊 Summary</p>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $products->count() }}</p>
                        <p class="text-sm text-gray-600">Total Products</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($products->sum('price'), 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">Total Value</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $products->where('is_seasonal', true)->count() }}</p>
                        <p class="text-sm text-gray-600">Seasonal Items</p>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('admin.shops') }}" class="inline-block px-6 py-2 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                    ← Back to Shops
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
