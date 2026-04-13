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
        <div class="flex flex-col gap-3 sm:gap-0 sm:flex-row sm:justify-between sm:items-center">
            <h2 class="font-bold text-2xl sm:text-3xl text-white leading-tight flex items-center gap-2">
                <i class="fas fa-receipt text-indigo-300"></i>
                Order {{ $order->formatted_id }}
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.orders') }}" class="text-sm text-white hover:text-indigo-200 transition-colors inline-flex items-center gap-2 bg-white bg-opacity-10 px-4 py-2 rounded-lg hover:bg-opacity-20">
                    <i class="fas fa-arrow-left"></i> Back to Admin
                </a>
            @else
                <a href="{{ route('pos.index') }}" class="text-sm text-white hover:text-indigo-200 transition-colors inline-flex items-center gap-2 bg-white bg-opacity-10 px-4 py-2 rounded-lg hover:bg-opacity-20">
                    <i class="fas fa-arrow-left"></i> Back to POS
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 sm:py-12" style="background-color: #242f6d;">
        <div class="max-w-full lg:max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            {{-- Order Header Info --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-hashtag text-indigo-600"></i> {{ __('pos.order_id') }}</p>
                    <p class="text-lg md:text-xl font-bold text-gray-900">{{ $order->formatted_id }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-store text-indigo-600"></i> SHOP</p>
                    <p class="text-sm md:text-base font-bold text-gray-900">
                        <i class="fas {{ $order->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $order->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }}; margin-right: 6px;"></i>{{ $order->shop->name }}
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-wallet text-indigo-600"></i> {{ __('pos.payment') }}</p>
                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        {{ $order->payment_type }}
                    </span>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-calendar text-indigo-600"></i> {{ __('pos.time') }}</p>
                    <p class="text-sm md:text-base font-bold text-gray-900">{{ $order->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</p>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-box text-indigo-600"></i> {{ __('pos.items') }}
                </h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg border-l-4 border-indigo-600 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                                <div class="flex-1">
                                    <h4 class="text-sm md:text-base font-bold text-gray-900">
                                        @if($item->product)
                                            {{ $item->product->name }}
                                        @else
                                            <span class="text-gray-500 italic">[Product Removed]</span>
                                        @endif
                                    </h4>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <i class="fas fa-tag mr-1"></i>
                                        Rp {{ number_format($item->price, 0, ',', '.') }} {{ __('pos.each') }}
                                    </p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 sm:text-right">
                                    <div>
                                        <p class="text-xs text-gray-600 font-semibold">{{ __('pos.quantity') }}</p>
                                        <p class="text-xl md:text-2xl font-bold text-indigo-600">{{ $item->quantity }}</p>
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
                    <i class="fas fa-calculator text-indigo-600"></i> {{ __('pos.total') }}
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
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-blue-700">Cash Received</span>
                                <span class="text-lg font-bold text-blue-900">Rp {{ number_format($order->cash_received, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-green-100 rounded-lg border-2 border-green-600">
                                <span class="text-sm font-bold text-green-900 flex items-center gap-2">
                                    <i class="fas fa-money-bill-wave"></i> Change
                                </span>
                                <span class="text-2xl font-bold text-green-900">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Employee & Shift Info --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-info-circle text-indigo-600"></i> {{ __('pos.amount') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                        <p class="text-xs font-bold text-indigo-700 mb-1 flex items-center gap-1"><i class="fas fa-user"></i> {{ __('pos.employee') }}</p>
                        <p class="text-base md:text-lg font-bold text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <p class="text-xs font-bold text-purple-700 mb-1 flex items-center gap-1"><i class="fas fa-clock"></i> SHIFT ID</p>
                        <p class="text-base md:text-lg font-bold text-gray-900">{{ $order->shift_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
