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

            {{-- Payment Method Breakdown --}}
            @if($shiftOrders->count() > 0)
                @php
                    $qrisOrders = $shiftOrders->where('payment_type', 'QRIS');
                    $cashOrders = $shiftOrders->where('payment_type', 'CASH');
                    $qrisTotal = $qrisOrders->sum('total_amount');
                    $cashTotal = $cashOrders->sum('total_amount');
                    $qrisCount = $qrisOrders->count();
                    $cashCount = $cashOrders->count();
                    $qrisItems = $qrisOrders->sum(function ($order) { return $order->items->sum('quantity'); });
                    $cashItems = $cashOrders->sum(function ($order) { return $order->items->sum('quantity'); });
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-6 mb-3 sm:mb-6">
                    {{-- QRIS Payment Breakdown --}}
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm p-4 sm:p-5 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-blue-500 text-white rounded-lg">
                                    <i class="fas fa-qrcode text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold">QRIS Payment</p>
                                    <p class="text-lg sm:text-xl font-bold text-blue-900">Rp {{ number_format($qrisTotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 bg-white bg-opacity-50 rounded-lg p-3">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $qrisCount }}</p>
                                <p class="text-xs text-gray-600 font-medium">Orders</p>
                            </div>
                            <div class="text-center border-l border-r border-blue-200">
                                <p class="text-2xl font-bold text-blue-600">{{ $qrisItems }}</p>
                                <p class="text-xs text-gray-600 font-medium">Items</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $qrisCount > 0 ? round($qrisTotal / $qrisCount) : 0 }}</p>
                                <p class="text-xs text-gray-600 font-medium">Avg</p>
                            </div>
                        </div>
                        
                        @if($qrisCount > 0)
                            <div class="mt-3 pt-3 border-t border-blue-200 space-y-1">
                                @foreach($qrisOrders->take(5) as $order)
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-700">{{ $order->formatted_id }}</span>
                                        <span class="font-semibold text-blue-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                @if($qrisCount > 5)
                                    <p class="text-xs text-gray-500 italic pt-1">+{{ $qrisCount - 5 }} more</p>
                                @endif
                            </div>
                        @else
                            <div class="mt-3 text-center text-sm text-gray-500">
                                No QRIS payments
                            </div>
                        @endif
                    </div>

                    {{-- CASH Payment Breakdown --}}
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm p-4 sm:p-5 border-l-4 border-green-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-green-500 text-white rounded-lg">
                                    <i class="fas fa-money-bill-wave text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-semibold">CASH Payment</p>
                                    <p class="text-lg sm:text-xl font-bold text-green-900">Rp {{ number_format($cashTotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 bg-white bg-opacity-50 rounded-lg p-3">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $cashCount }}</p>
                                <p class="text-xs text-gray-600 font-medium">Orders</p>
                            </div>
                            <div class="text-center border-l border-r border-green-200">
                                <p class="text-2xl font-bold text-green-600">{{ $cashItems }}</p>
                                <p class="text-xs text-gray-600 font-medium">Items</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $cashCount > 0 ? round($cashTotal / $cashCount) : 0 }}</p>
                                <p class="text-xs text-gray-600 font-medium">Avg</p>
                            </div>
                        </div>
                        
                        @if($cashCount > 0)
                            <div class="mt-3 pt-3 border-t border-green-200 space-y-1">
                                @foreach($cashOrders->take(5) as $order)
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-700">{{ $order->formatted_id }}</span>
                                        <span class="font-semibold text-green-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                @if($cashCount > 5)
                                    <p class="text-xs text-gray-500 italic pt-1">+{{ $cashCount - 5 }} more</p>
                                @endif
                            </div>
                        @else
                            <div class="mt-3 text-center text-sm text-gray-500">
                                No CASH payments
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Method Statistics --}}
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-5 mb-3 sm:mb-6">
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 mb-4">Payment Method Summary</h3>
                    
                    <div class="space-y-3">
                        {{-- QRIS Bar --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-qrcode text-blue-500 text-sm"></i>
                                    <span class="text-sm font-semibold text-gray-700">QRIS</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $shiftTotalAmount > 0 ? round(($qrisTotal / $shiftTotalAmount) * 100) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $shiftTotalAmount > 0 ? ($qrisTotal / $shiftTotalAmount) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        {{-- CASH Bar --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-money-bill-wave text-green-500 text-sm"></i>
                                    <span class="text-sm font-semibold text-gray-700">CASH</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $shiftTotalAmount > 0 ? round(($cashTotal / $shiftTotalAmount) * 100) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $shiftTotalAmount > 0 ? ($cashTotal / $shiftTotalAmount) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Order Details Breakdown by Payment Method --}}
            @if($shiftOrders->count() > 0)
                <div class="space-y-4 sm:space-y-6">
                    {{-- QRIS Product Details --}}
                    @if($qrisProductDetails->count() > 0)
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm p-4 sm:p-5 border-l-4 border-blue-500">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="p-2 bg-blue-500 text-white rounded-lg">
                                        <i class="fas fa-qrcode text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm sm:text-base font-bold text-gray-900">QRIS - Products Sold</h3>
                                        <p class="text-xs text-gray-600">{{ $qrisProductDetails->sum('quantity') }} items total</p>
                                    </div>
                                </div>
                                <button onclick="copyQRISReport()" class="flex items-center gap-2 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs sm:text-sm font-semibold rounded-lg transition-colors whitespace-nowrap">
                                    <i class="fas fa-copy"></i>
                                    <span>Copy Report</span>
                                </button>
                            </div>

                            {{-- Hidden QRIS Report Data --}}
                            <div id="qris-report-data" style="display: none;">
@php
$qrisTotal = $qrisProductDetails->sum('total');
$qrisItems = $qrisProductDetails->sum('quantity');
$reportText = "QRIS - PRODUCTS SOLD\n";
$reportText .= "Total Rp: " . number_format($qrisTotal, 0, ',', '.') . "\n";
$reportText .= "Total Items: " . $qrisItems . "\n";
$reportText .= str_repeat("=", 50) . "\n\n";
$reportText .= "Product Sold Details:\n";
$qrisCounter = 1;
foreach($qrisProductDetails as $product):
    $reportText .= $qrisCounter . ". " . $product['product_name'] . " - " . $product['product_type'] . " = " . $product['quantity'] . " items (Rp " . number_format($product['total'], 0, ',', '.') . ")\n";
    $qrisCounter++;
endforeach;
@endphp
{{ $reportText }}
                            </div>

                            <div class="space-y-2">
                                @foreach($qrisProductDetails as $product)
                                    <div class="bg-white bg-opacity-80 rounded-lg p-3 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <p class="font-semibold text-sm text-gray-900">
                                                    <span class="text-blue-600 font-bold mr-2">{{ $loop->iteration }}.</span>{{ $product['product_name'] }}
                                                </p>
                                                <p class="text-xs text-blue-700 font-medium">{{ $product['product_type'] }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-bold text-blue-600">{{ $product['quantity'] }} items</p>
                                                <p class="text-xs text-gray-500">Rp {{ number_format($product['total'], 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <div class="w-full bg-blue-100 rounded-full h-1.5">
                                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ ($product['quantity'] / $qrisProductDetails->sum('quantity')) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- CASH Product Details --}}
                    @if($cashProductDetails->count() > 0)
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm p-4 sm:p-5 border-l-4 border-green-500">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="p-2 bg-green-500 text-white rounded-lg">
                                        <i class="fas fa-money-bill-wave text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm sm:text-base font-bold text-gray-900">CASH - Products Sold</h3>
                                        <p class="text-xs text-gray-600">{{ $cashProductDetails->sum('quantity') }} items total</p>
                                    </div>
                                </div>
                                <button onclick="copyCASHReport()" class="flex items-center gap-2 px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-xs sm:text-sm font-semibold rounded-lg transition-colors whitespace-nowrap">
                                    <i class="fas fa-copy"></i>
                                    <span>Copy Report</span>
                                </button>
                            </div>

                            {{-- Hidden CASH Report Data --}}
                            <div id="cash-report-data" style="display: none;">
@php
$cashTotal = $cashProductDetails->sum('total');
$cashItems = $cashProductDetails->sum('quantity');
$reportText = "CASH - PRODUCTS SOLD\n";
$reportText .= "Total Rp: " . number_format($cashTotal, 0, ',', '.') . "\n";
$reportText .= "Total Items: " . $cashItems . "\n";
$reportText .= str_repeat("=", 50) . "\n\n";
$reportText .= "Product Sold Details:\n";
$cashCounter = 1;
foreach($cashProductDetails as $product):
    $reportText .= $cashCounter . ". " . $product['product_name'] . " - " . $product['product_type'] . " = " . $product['quantity'] . " items (Rp " . number_format($product['total'], 0, ',', '.') . ")\n";
    $cashCounter++;
endforeach;
@endphp
{{ $reportText }}
                            </div>

                            <div class="space-y-2">
                                @foreach($cashProductDetails as $product)
                                    <div class="bg-white bg-opacity-80 rounded-lg p-3 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <p class="font-semibold text-sm text-gray-900">
                                                    <span class="text-green-600 font-bold mr-2">{{ $loop->iteration }}.</span>{{ $product['product_name'] }}
                                                </p>
                                                <p class="text-xs text-green-700 font-medium">{{ $product['product_type'] }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-bold text-green-600">{{ $product['quantity'] }} items</p>
                                                <p class="text-xs text-gray-500">Rp {{ number_format($product['total'], 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <div class="w-full bg-green-100 rounded-full h-1.5">
                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ ($product['quantity'] / $cashProductDetails->sum('quantity')) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyQRISReport() {
            const reportData = document.getElementById('qris-report-data').textContent.trim();
            copyToClipboard(reportData, 'QRIS Report');
        }

        function copyCASHReport() {
            const reportData = document.getElementById('cash-report-data').textContent.trim();
            copyToClipboard(reportData, 'CASH Report');
        }

        function copyToClipboard(text, reportType) {
            if (!text) {
                alert('No data to copy');
                return;
            }

            // Create a temporary textarea element
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);

            // Select and copy the text
            textarea.select();
            try {
                document.execCommand('copy');
                
                // Show success feedback
                showCopyNotification(reportType);
            } catch (err) {
                alert('Failed to copy report');
            } finally {
                document.body.removeChild(textarea);
            }
        }

        function showCopyNotification(reportType) {
            // Create and show a temporary notification
            const notification = document.createElement('div');
            notification.textContent = reportType + ' copied to clipboard!';
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background-color: #10b981;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                font-weight: 600;
                z-index: 9999;
                animation: slideIn 0.3s ease-out;
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // CSS animations for notifications
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>
