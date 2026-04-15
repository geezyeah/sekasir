<x-app-layout>
    @php
        $bgColor = $order->shop->getProperty('bg_color', '#8d140c');
        $textColor = $order->shop->getProperty('text_color', '#F5E6D3');
        $primaryColor = $order->shop->getProperty('primary_color', '#9d2121');
    @endphp
    
    <style>
        body {
            background-color: {{ $bgColor }};
        }
        header {
            background-color: {{ $bgColor }} !important;
        }
        nav {
            background-color: {{ $bgColor }} !important;
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:gap-0 sm:flex-row sm:justify-between sm:items-center">
            <h2 class="font-bold text-2xl sm:text-3xl leading-tight flex items-center gap-2" style="color: {{ $textColor }};">
                <i class="fas fa-receipt" style="color: {{ $textColor }}; opacity: 0.8;"></i>
                Order {{ $order->formatted_id }}
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.orders') }}" class="text-sm transition-colors inline-flex items-center gap-2 bg-white bg-opacity-10 px-4 py-2 rounded-lg hover:bg-opacity-20" style="color: {{ $textColor }};">
                    <i class="fas fa-arrow-left"></i> Back to Admin
                </a>
            @else
                <a href="{{ route('pos.index') }}" class="text-sm transition-colors inline-flex items-center gap-2 bg-white bg-opacity-10 px-4 py-2 rounded-lg hover:bg-opacity-20" style="color: {{ $textColor }};">
                    <i class="fas fa-arrow-left"></i> Back to POS
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 sm:py-12" style="background-color: {{ $bgColor }};">
        <div class="max-w-full lg:max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            {{-- Order Header Info --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-hashtag" style="color: {{ $bgColor }};"></i> {{ __('pos.order_id') }}</p>
                    <p class="text-lg md:text-xl font-bold text-gray-900">{{ $order->formatted_id }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-store" style="color: {{ $bgColor }};"></i> SHOP</p>
                    <p class="text-sm md:text-base font-bold text-gray-900">
                        <i class="fas {{ $order->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $order->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }}; margin-right: 6px;"></i>{{ $order->shop->name }}
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-wallet" style="color: {{ $bgColor }};"></i> {{ __('pos.payment') }}</p>
                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        {{ $order->payment_type }}
                    </span>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-calendar" style="color: {{ $bgColor }};"></i> {{ __('pos.time') }}</p>
                    <p class="text-sm md:text-base font-bold text-gray-900">{{ $order->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</p>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-box" style="color: {{ $bgColor }};"></i> {{ __('pos.items') }}
                </h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        @php
                            $typeIcon = 'fa-ice-cream';
                            $typeColor = '#c41e3a';
                            
                            if($item->product?->productType) {
                                $typeName = strtolower($item->product->productType->name);
                                
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
                                } elseif(str_contains($typeName, 'drink')) {
                                    $typeIcon = 'fa-bottle-water';
                                    $typeColor = '#3498db';
                                }
                            }
                        @endphp
                        <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg hover:shadow-md transition-shadow" style="border-left: 4px solid {{ $bgColor }};">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="flex-shrink-0">
                                            <i class="fas {{ $typeIcon }} text-xl" style="color: {{ $typeColor }};"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm md:text-base font-bold text-gray-900">
                                                @if($item->product)
                                                    {{ $item->product->name }}
                                                @else
                                                    <span class="text-gray-500 italic">[Product Removed]</span>
                                                @endif
                                            </h4>
                                            @if($item->product?->productType)
                                                <p class="text-xs font-medium mt-0.5" style="color: {{ $typeColor }}; opacity: 0.8;">
                                                    {{ strtoupper($item->product->productType->name) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-600">
                                        <i class="fas fa-tag mr-1"></i>
                                        Rp {{ number_format($item->price, 0, ',', '.') }} {{ __('pos.each') }}
                                    </p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 sm:text-right">
                                    <div>
                                        <p class="text-xs text-gray-600 font-semibold">{{ __('pos.quantity') }}</p>
                                        <p class="text-xl md:text-2xl font-bold" style="color: {{ $bgColor }};">{{ $item->quantity }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 font-semibold">{{ __('pos.subtotal') }}</p>
                                        <p class="text-lg md:text-2xl font-bold text-green-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-calculator" style="color: {{ $bgColor }};"></i> {{ __('pos.total') }}
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">{{ __('pos.subtotal') }}</span>
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="pt-4 border-t-2 border-gray-300">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">{{ __('pos.total') }}</span>
                            <span class="text-3xl font-bold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($order->payment_type === 'CASH')
                        <div class="pt-4 border-t-2 border-gray-300 space-y-3">
                            <div class="flex justify-between items-center p-3 rounded-lg" style="background-color: {{ $bgColor }}20;">
                                <span class="text-sm font-medium" style="color: {{ $bgColor }};">Cash Received</span>
                                <span class="text-lg font-bold" style="color: {{ $bgColor }};">Rp {{ number_format($order->cash_received, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 rounded-lg border-2" style="background-color: {{ $bgColor }}20; border-color: {{ $bgColor }};">
                                <span class="text-sm font-bold flex items-center gap-2" style="color: {{ $bgColor }};">
                                    <i class="fas fa-money-bill-wave"></i> Change
                                </span>
                                <span class="text-2xl font-bold" style="color: {{ $bgColor }};">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Employee & Shift Info --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-info-circle" style="color: {{ $bgColor }};"></i> {{ __('pos.amount') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-lg border" style="background-color: {{ $bgColor }}20; border-color: {{ $bgColor }};">
                        <p class="text-xs font-bold mb-1 flex items-center gap-1" style="color: {{ $bgColor }};"><i class="fas fa-user"></i> {{ __('pos.employee') }}</p>
                        <p class="text-base md:text-lg font-bold text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div class="p-4 rounded-lg border" style="background-color: {{ $bgColor }}20; border-color: {{ $bgColor }};">
                        <p class="text-xs font-bold mb-1 flex items-center gap-1" style="color: {{ $bgColor }};"><i class="fas fa-clock"></i> SHIFT ID</p>
                        <p class="text-base md:text-lg font-bold text-gray-900">{{ $order->shift_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
