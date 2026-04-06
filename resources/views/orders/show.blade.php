<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:gap-0 sm:flex-row sm:justify-between sm:items-center">
            <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">
                Order {{ $order->formatted_id }}
            </h2>
            <a href="{{ route('pos.index') }}" class="text-xs sm:text-sm text-white hover:text-white self-start sm:self-auto">← Back to POS</a>
        </div>
    </x-slot>

    <div class="py-2 sm:py-6">
        <div class="max-w-3xl mx-auto px-2 sm:px-6 lg:px-8">
            {{-- Order Header --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-6 mb-3 sm:mb-6">
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Order ID</p>
                        <p class="text-base sm:text-2xl font-bold text-gray-900">{{ $order->formatted_id }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Shop</p>
                        <p class="text-xs sm:text-lg font-semibold text-gray-900">
                            <i class="fas {{ $order->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $order->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }}; margin-right: 8px;"></i>{{ $order->shop->name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Payment</p>
                        <span class="inline-block px-2 sm:px-3 py-0.5 text-xs sm:text-sm rounded-full font-semibold {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $order->payment_type }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Date & Time</p>
                        <p class="text-xs sm:text-lg font-semibold text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-6 mb-3 sm:mb-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Order Items</h3>
                <div class="space-y-2 sm:space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-4 p-2 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} each</p>
                            </div>
                            <div class="flex gap-3 sm:gap-4">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Qty</p>
                                    <p class="text-base sm:text-xl font-bold text-gray-900">{{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 mb-1">Subtotal</p>
                                    <p class="text-sm sm:text-lg font-bold text-green-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-6 mb-3 sm:mb-6">
                <div class="space-y-2 sm:space-y-4">
                    <div class="flex justify-between items-center text-sm sm:text-base">
                        <span class="text-gray-700">Subtotal</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 sm:pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center text-base sm:text-lg">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-lg sm:text-2xl font-bold text-green-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($order->payment_type === 'CASH')
                        <div class="pt-2 sm:pt-4 border-t border-gray-200">
                            <div class="space-y-2 sm:space-y-3">
                                <div class="flex justify-between items-center text-sm sm:text-base">
                                    <span class="text-gray-700">Cash Received</span>
                                    <span class="font-semibold text-gray-900">Rp {{ number_format($order->cash_received, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-green-50 p-2 sm:p-3 rounded-lg">
                                    <span class="text-xs sm:text-sm font-semibold text-green-700">Change</span>
                                    <span class="text-base sm:text-lg font-bold text-green-700">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Employee & Shift Info --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Served By</p>
                        <p class="text-sm sm:text-lg font-semibold text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Shift ID</p>
                        <p class="text-sm sm:text-lg font-semibold text-gray-900">{{ $order->shift_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
