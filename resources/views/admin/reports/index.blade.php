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
        @media (max-width: 640px) {
            body {
                font-size: 14px;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">{{ __('admin.reports_title') }}</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.reports'), 'label' => 'Reports']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-8 lg:max-w-7xl">
            <!-- Filters -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-2 sm:p-6 mb-3 sm:mb-6">
                <form method="GET" action="{{ route('admin.reports') }}" class="space-y-2 sm:space-y-0 sm:flex sm:flex-wrap sm:gap-3 sm:items-end">
                    <div class="flex-1 min-w-fit sm:min-w-max">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Shop</label>
                        <select name="shop_id" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                            <option value="">All Shops</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ $shopId == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-fit sm:min-w-max">
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('admin.start_date') }}</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                    </div>
                    <div class="flex-1 min-w-fit sm:min-w-max">
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('admin.end_date') }}</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                    </div>
                    <div class="flex gap-1 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-none bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">{{ __('admin.generate') }}</button>
                        <a href="{{ route('admin.reports') }}" class="flex-1 sm:flex-none text-xs text-gray-500 hover:text-gray-700 px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-center">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Key Metrics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-3 sm:mb-6">
                <!-- Total Revenue -->
                <div class="bg-white rounded-lg p-2 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-2 sm:mb-0">
                            <p class="text-xs text-gray-500">{{ __('admin.total_revenue') }}</p>
                            <p class="text-base sm:text-2xl font-bold text-gray-900">Rp {{ number_format($revenueStats['total_revenue'], 0, ',', '.') }}</p>
                        </div>
                        <div class="p-2 bg-blue-100 rounded-lg w-fit sm:w-auto">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white rounded-lg p-2 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-2 sm:mb-0">
                            <p class="text-xs text-gray-500">Total Orders</p>
                            <p class="text-base sm:text-2xl font-bold text-gray-900">{{ number_format($revenueStats['total_orders']) }}</p>
                        </div>
                        <div class="p-2 bg-green-100 rounded-lg w-fit sm:w-auto">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 1a1 1 0 000 2c16.417 0 16 14.6 16 14.6V9a1 1 0 112 0v8a2 2 0 01-2 2H3a1 1 0 110-2h14V3a1 1 0 000-2H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Order Value -->
                <div class="bg-white rounded-lg p-2 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-2 sm:mb-0">
                            <p class="text-xs text-gray-500">{{ __('admin.average_order_value') }}</p>
                            <p class="text-base sm:text-2xl font-bold text-gray-900">Rp {{ number_format($revenueStats['average_order_value'], 0, ',', '.') }}</p>
                        </div>
                        <div class="p-2 bg-purple-100 rounded-lg w-fit sm:w-auto">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.16 2.75a.75.75 0 00-1.08.6v2.38H3.75a.75.75 0 000 1.5h3.33v7.62H3.75a.75.75 0 000 1.5h12.5a.75.75 0 000-1.5h-3.33V7.23h3.33a.75.75 0 000-1.5h-3.33V3.35a.75.75 0 00-.75-.6z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Items -->
                <div class="bg-white rounded-lg p-2 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-2 sm:mb-0">
                            <p class="text-xs text-gray-500">Total Items Sold</p>
                            <p class="text-base sm:text-2xl font-bold text-gray-900">{{ number_format($revenueStats['total_items']) }}</p>
                        </div>
                        <div class="p-2 bg-orange-100 rounded-lg w-fit sm:w-auto">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2c.022 0 .092.735.5 2.05C4.063 5.407 5.5 9.057 10 9.5c4.5-.443 5.937-4.093 6.5-6.45.408-1.315.478-2.05.5-2.05a1 1 0 000-2h-17z" />
                                <path d="M3 11a1 1 0 011-1h12a1 1 0 011 1v7a1 1 0 01-1 1H4a1 1 0 01-1-1v-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 sm:gap-4 mb-3 sm:mb-6">
                <!-- Daily Revenue Chart -->
                <div class="bg-white rounded-lg p-2 sm:p-4 shadow-sm">
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Daily Revenue Trend</h3>
                    <div style="position: relative; height: 250px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Payment Method Breakdown -->
                <div class="bg-white rounded-lg p-2 sm:p-4 shadow-sm">
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Payment Method Breakdown</h3>
                    <div style="position: relative; height: 250px;">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- More Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 sm:gap-4 mb-3 sm:mb-6">
                <!-- Revenue by Shop -->
                @if(!$shopId)
                <div class="bg-white rounded-lg p-2 sm:p-4 shadow-sm">
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Revenue by Shop</h3>
                    <div style="position: relative; height: 250px;">
                        <canvas id="shopChart"></canvas>
                    </div>
                </div>
                @endif

                <!-- Order Peak Hours -->
                <div class="bg-white rounded-lg p-2 sm:p-4 shadow-sm">
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Peak Hours</h3>
                    <div class="space-y-2 sm:space-y-3">
                        <div>
                            <p class="text-xs text-gray-600">Busiest Hour</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900">
                                @if($orderMetrics['peak_hour'] !== null)
                                    {{ str_pad($orderMetrics['peak_hour'], 2, '0', STR_PAD_LEFT) }}:00 ({{ $orderMetrics['peak_hour_count'] }} orders)
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Average Order Value</p>
                            <p class="text-lg sm:text-xl font-bold text-gray-900">Rp {{ number_format($orderMetrics['avg_order_value'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products Table -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-2 sm:p-4 mb-3 sm:mb-6">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Top 10 Products</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Revenue</th>
                                <th class="px-2 sm:px-4 py-2 hidden sm:table-cell text-left font-medium text-gray-500 uppercase">Avg Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($topProducts as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 sm:px-4 py-2 font-medium text-gray-900">{{ Str::limit($product->name, 12) }}</td>
                                    <td class="px-2 sm:px-4 py-2 text-gray-600">{{ $product->total_quantity }}</td>
                                    <td class="px-2 sm:px-4 py-2 font-medium text-gray-900">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                    <td class="px-2 sm:px-4 py-2 hidden sm:table-cell text-gray-600">Rp {{ number_format($product->avg_price, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">No products found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Employee Performance Table -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-2 sm:p-4 mb-3 sm:mb-6">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Employee Performance</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Orders</th>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Revenue</th>
                                <th class="px-2 sm:px-4 py-2 hidden md:table-cell text-left font-medium text-gray-500 uppercase">Avg Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($employeePerformance as $employee)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 sm:px-4 py-2 font-medium text-gray-900">{{ Str::limit($employee->name, 10) }}</td>
                                    <td class="px-2 sm:px-4 py-2 text-gray-600">{{ $employee->order_count }}</td>
                                    <td class="px-2 sm:px-4 py-2 font-medium text-gray-900">Rp {{ number_format($employee->total_revenue, 0, ',', '.') }}</td>
                                    <td class="px-2 sm:px-4 py-2 hidden md:table-cell text-gray-600">Rp {{ number_format($employee->avg_order_value, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">No employee data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Shift Analytics Table -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-2 sm:p-4 mb-3 sm:mb-6">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Shift Analytics</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Shifts</th>
                                <th class="px-2 sm:px-4 py-2 hidden sm:table-cell text-left font-medium text-gray-500 uppercase">Avg Dur.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($shiftAnalytics as $shift)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 sm:px-4 py-2 font-medium text-gray-900">{{ Str::limit($shift->name, 10) }}</td>
                                    <td class="px-2 sm:px-4 py-2 text-gray-600">{{ $shift->shift_count }}</td>
                                    <td class="px-2 sm:px-4 py-2 hidden sm:table-cell text-gray-600 text-xs">{{ $shift->avg_duration ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">No shift data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Revenue by Shop Table (if not filtered) -->
            @if(!$shopId)
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-2 sm:p-4">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Shop Summary</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Shop</th>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Orders</th>
                                <th class="px-2 sm:px-4 py-2 text-left font-medium text-gray-500 uppercase">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($revenueByShop as $shop)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 sm:px-4 py-2 font-medium text-gray-900">{{ $shop->name }}</td>
                                    <td class="px-2 sm:px-4 py-2 text-gray-600">{{ $shop->order_count }}</td>
                                    <td class="px-2 sm:px-4 py-2 font-medium text-gray-900">Rp {{ number_format($shop->revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">No shop data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Daily Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
                    datasets: [
                        {
                            label: 'Revenue (Rp)',
                            data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#4f46e5',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Order Count',
                            data: {!! json_encode($dailyRevenue->pluck('order_count')) !!},
                            borderColor: '#06b6d4',
                            backgroundColor: 'rgba(6, 182, 212, 0.1)',
                            yAxisID: 'y1',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#06b6d4',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID', {notation: 'compact'}).format(value);
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            }
                        }
                    }
                }
            });
        }

        // Payment Method Chart
        const paymentCtx = document.getElementById('paymentChart');
        if (paymentCtx) {
            const paymentData = {!! json_encode($paymentBreakdown) !!};
            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentData.map(p => p.payment_type),
                    datasets: [{
                        data: paymentData.map(p => p.revenue),
                        backgroundColor: [
                            '#4f46e5',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                        ],
                        borderColor: '#fff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }

        // Revenue by Shop Chart
        const shopCtx = document.getElementById('shopChart');
        if (shopCtx) {
            const shopData = {!! json_encode($revenueByShop) !!};
            new Chart(shopCtx, {
                type: 'bar',
                data: {
                    labels: shopData.map(s => s.name),
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: shopData.map(s => s.revenue),
                        backgroundColor: [
                            '#4f46e5',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6',
                        ],
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID', {notation: 'compact'}).format(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
