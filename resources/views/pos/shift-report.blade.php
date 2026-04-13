<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-4">
            <h2 class="font-bold text-lg sm:text-xl leading-tight" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                {{ $shop->name }} {{ __('pos.shift_report') }}
            </h2>
            <a href="{{ route('pos.index') }}" class="text-xs sm:text-sm transition-colors self-start sm:self-auto" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }}; opacity: 0.8;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">← {{ __('pos.end_shift') }}</a>
        </div>
    </x-slot>
    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('dashboard'), 'label' => 'Dashboard'],
        ['url' => route('pos.index'), 'label' => 'POS'],
        ['url' => route('pos.shift-report'), 'label' => 'Shift Report']
    ]" />
    <div class="py-2 sm:py-6">
        <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">
            {{-- Shift Summary Cards --}}
            <div class="grid grid-cols-3 md:grid-cols-3 gap-1.5 sm:gap-3 mb-3 sm:mb-6">
                {{-- Employee --}}
                <div class="bg-white rounded-lg shadow-sm p-2 sm:p-3 border-l-3 border-blue-500">
                    <p class="text-xs text-gray-500 font-medium mb-0.5">{{ __('pos.employee') }}</p>
                    <p class="text-xs sm:text-sm font-bold text-gray-900">{{ $shift->user->name }}</p>
                </div>

                {{-- Shift Duration --}}
                <div class="bg-white rounded-lg shadow-sm p-2 sm:p-3 border-l-3 border-green-500">
                    <p class="text-xs text-gray-500 font-medium mb-0.5">{{ __('pos.started') }}</p>
                    <p class="text-xs sm:text-sm font-bold text-gray-900">{{ $shift->login_time->format('d M H:i') }}</p>
                </div>

                {{-- Total Orders --}}
                <div class="bg-white rounded-lg shadow-sm p-2 sm:p-3 border-l-3 border-purple-500">
                    <p class="text-xs text-gray-500 font-medium mb-0.5">{{ __('pos.items') }}</p>
                    <p class="text-xs sm:text-sm font-bold text-gray-900">{{ $shiftOrders->count() }}</p>
                </div>
            </div>

            {{-- Total Amount Highlight --}}
            <div class="bg-gradient-to-br rounded-lg shadow-md p-3 sm:p-5 mb-3 sm:mb-6 text-white" style="background: linear-gradient(to bottom right, {{ $shop->getProperty('bg_color', '#8d140c') }}, {{ $shop->getProperty('primary_color', '#9d2121') }});">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                    <div>
                        <p class="text-xs uppercase font-semibold tracking-wide opacity-90 mb-0.5">{{ __('pos.total') }}</p>
                        <p class="text-xl sm:text-2xl font-bold">Rp {{ number_format($shiftTotalAmount, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right sm:text-center">
                        <p class="text-2xl sm:text-3xl font-bold">{{ $totalItems }}</p>
                        <p class="text-xs opacity-90">{{ __('pos.items_sold') }}</p>
                    </div>
                </div>
            </div>

            {{-- Orders Table/Cards --}}
            <div class="bg-white rounded-lg shadow-sm p-3 sm:p-5 mb-3 sm:mb-6">
                <h3 class="text-sm sm:text-base font-bold text-gray-900 mb-3 sm:mb-4">{{ __('pos.items') }}</h3>

                @if($shiftOrders->count() > 0)
                    {{-- Mobile View (Cards) --}}
                    <div class="md:hidden space-y-2">
                        @foreach($shiftOrders as $order)
                            <a href="{{ route('orders.show', $order) }}" class="block p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-semibold text-sm" style="color: {{ $shop->getProperty('bg_color', '#8d140c') }};">{{ $order->formatted_id }}</span>
                                    <span class="text-xs rounded-full font-semibold px-2 py-0.5 {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $order->payment_type }}
                                    </span>
                                </div>
                                <div class="space-y-1 text-xs text-gray-600">
                                    <p>{{ $order->created_at->format('H:i:s') }} • {{ $order->items->sum('quantity') }} items</p>
                                    <p class="font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Desktop View (Table) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="border-b border-gray-200">
                                <tr>
                                    <th class="text-left py-2 px-2 font-semibold text-gray-700">{{ __('pos.order_id') }}</th>
                                    <th class="text-left py-2 px-2 font-semibold text-gray-700">{{ __('pos.time') }}</th>
                                    <th class="text-left py-2 px-2 font-semibold text-gray-700">{{ __('pos.items') }}</th>
                                    <th class="text-left py-2 px-2 font-semibold text-gray-700">{{ __('pos.payment') }}</th>
                                    <th class="text-right py-2 px-2 font-semibold text-gray-700">{{ __('pos.amount') }}</th>
                                    <th class="text-center py-2 px-2 font-semibold text-gray-700">{{ __('pos.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($shiftOrders as $order)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-2 px-2 font-semibold" style="color: {{ $shop->getProperty('bg_color', '#8d140c') }};">{{ $order->formatted_id }}</td>
                                        <td class="py-2 px-2 text-gray-600">{{ $order->created_at->format('H:i:s') }}</td>
                                        <td class="py-2 px-2 text-gray-600">{{ $order->items->sum('quantity') }}</td>
                                        <td class="py-2 px-2">
                                            <span class="inline-block px-2 py-0.5 text-xs rounded-full font-semibold {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $order->payment_type }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-2 text-right font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="py-2 px-2 text-center">
                                            <a href="{{ route('orders.show', $order) }}" class="text-xs font-semibold transition-colors" style="color: {{ $shop->getProperty('bg_color', '#8d140c') }};">
                                                View →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6 sm:py-8">
                        <p class="text-gray-500 text-sm">{{ __('pos.no_orders_yet') }}</p>
                    </div>
                @endif
            </div>

            {{-- Order Details Breakdown --}}
            @if($shiftOrders->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-3 sm:p-5">
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 mb-3 sm:mb-4">{{ __('pos.products_summary') }}</h3>
                    
                    @php
                        $productSummary = collect();
                        foreach ($shiftOrders as $order) {
                            foreach ($order->items as $item) {
                                $key = $item->product?->name ?? '[Product Removed]';
                                if ($productSummary->has($key)) {
                                    $productSummary[$key] = [
                                        'quantity' => $productSummary[$key]['quantity'] + $item->quantity,
                                        'total' => $productSummary[$key]['total'] + ($item->price * $item->quantity),
                                    ];
                                } else {
                                    $productSummary[$key] = [
                                        'quantity' => $item->quantity,
                                        'total' => $item->price * $item->quantity,
                                    ];
                                }
                            }
                        }
                    @endphp

                    <div class="space-y-1">
                        @foreach($productSummary as $productName => $details)
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded border border-gray-200">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">{{ $productName }}</p>
                                    <p class="text-xs text-gray-500">Qty: {{ $details['quantity'] }}</p>
                                </div>
                                <p class="font-bold text-sm text-green-700">Rp {{ number_format($details['total'], 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
