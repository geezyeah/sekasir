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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">Admin Dashboard</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-8">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 lg:gap-6 mb-3 sm:mb-8">
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-2.5 sm:p-4 lg:p-6 border-l-4 border-indigo-600 hover:shadow-xl transition-shadow">
                    <div class="flex flex-col gap-2">
                        <div class="p-2 sm:p-3 bg-indigo-100 rounded-full w-fit">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Today's Orders</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $todayOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-2.5 sm:p-4 lg:p-6 border-l-4 border-green-600 hover:shadow-xl transition-shadow">
                    <div class="flex flex-col gap-2">
                        <div class="p-2 sm:p-3 bg-green-100 rounded-full w-fit">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Today's Revenue</p>
                            <p class="text-xs sm:text-2xl font-bold text-gray-900 truncate">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-2.5 sm:p-4 lg:p-6 border-l-4 border-blue-600 hover:shadow-xl transition-shadow">
                    <div class="flex flex-col gap-2">
                        <div class="p-2 sm:p-3 bg-blue-100 rounded-full w-fit">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Active Shifts</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $activeShifts->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-2.5 sm:p-4 lg:p-6 border-l-4 border-purple-600 hover:shadow-xl transition-shadow">
                    <div class="flex flex-col gap-2">
                        <div class="p-2 sm:p-3 bg-purple-100 rounded-full w-fit">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Total Employees</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $totalEmployees }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Breakdown by Shop --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-6 border-t-4 border-indigo-600 mb-3 sm:mb-8">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Today's Payment Breakdown by Shop</h3>
                
                {{-- Summary Cards --}}
                <div class="grid grid-cols-2 gap-2 sm:gap-4 mb-4 sm:mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 sm:p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center gap-2">
                            <span class="text-lg sm:text-2xl">📱</span>
                            <div>
                                <p class="text-xs text-gray-600">Total QRIS</p>
                                <p class="text-sm sm:text-lg font-bold text-blue-700">Rp {{ number_format($paymentBreakdown['QRIS'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-3 sm:p-4 rounded-lg border border-green-200">
                        <div class="flex items-center gap-2">
                            <span class="text-lg sm:text-2xl">💵</span>
                            <div>
                                <p class="text-xs text-gray-600">Total CASH</p>
                                <p class="text-sm sm:text-lg font-bold text-green-700">Rp {{ number_format($paymentBreakdown['CASH'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shop Breakdown --}}
                <div class="space-y-2 sm:space-y-3">
                    @foreach($paymentBreakdownByShop as $shopName => $breakdown)
                        @if($breakdown['total'] > 0)
                            <div class="p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-base sm:text-xl"><i class="fas {{ $shopName === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $shopName === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i></span>
                                        <span class="font-semibold text-xs sm:text-sm text-gray-900">{{ $shopName }}</span>
                                    </div>
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-semibold">Rp {{ number_format($breakdown['total'], 0, ',', '.') }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="flex justify-between p-2 bg-white rounded">
                                        <span class="text-gray-600">QRIS:</span>
                                        <span class="font-semibold text-blue-600">Rp {{ number_format($breakdown['QRIS'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between p-2 bg-white rounded">
                                        <span class="text-gray-600">CASH:</span>
                                        <span class="font-semibold text-green-600">Rp {{ number_format($breakdown['CASH'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-2">
                                    <span class="text-base sm:text-xl"><i class="fas {{ $shopName === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $shopName === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i></span>
                                    <span class="font-semibold text-xs sm:text-sm text-gray-600">{{ $shopName }}</span>
                                    <span class="text-xs text-gray-400">- No transactions today</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Active Shifts --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-6 mb-3 sm:mb-8 border-t-4 border-blue-600">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Active Shifts</h3>
                @if($activeShifts->isEmpty())
                    <p class="text-xs sm:text-sm text-gray-500">No active shifts right now.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3 lg:gap-4">
                        @foreach($activeShifts as $shift)
                            <div class="p-2 sm:p-4 bg-green-50 border border-green-200 rounded-lg sm:rounded-xl">
                                <div class="flex items-center justify-between flex-col sm:flex-row gap-2">
                                    <div>
                                        <p class="text-xs sm:text-base font-semibold text-gray-900">{{ $shift->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $shift->shop->name }}</p>
                                    </div>
                                <span class="px-2 py-0.5 sm:py-1 bg-gradient-to-r from-green-100 to-green-200 text-green-800 text-xs font-semibold rounded-full">Active</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 sm:mt-2">Since {{ $shift->login_time?->format('H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-6 border-t-4 border-purple-600">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Recent Orders</h3>

                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-2">
                    @forelse($recentOrders as $order)
                        <div class="p-2 bg-gradient-to-r from-gray-50 to-indigo-50 rounded-lg border border-indigo-200">
                            <div class="flex justify-between items-start mb-1">
                                <p class="font-semibold text-xs text-gray-900">{{ $order->formatted_id }}</p>
                                <span class="text-xs font-bold px-2 py-1 rounded {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ $order->payment_type }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mb-1">{{ $order->shop->name }}</p>
                            <p class="text-xs font-semibold text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M H:i') }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">No recent orders</p>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 border-b-2 border-indigo-300">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">#</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Employee</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Shop</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Items</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Total</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Payment</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-indigo-100 transition-colors">
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-medium text-gray-900">{{ $order->id }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-600">{{ Str::limit($order->user->name, 8) }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-600">{{ $order->shop->name === 'Ice Lepen' ? '🍦' : '🥟' }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-600">{{ $order->items->sum('quantity') }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $order->payment_type }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-600">{{ $order->created_at->format('H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-2 sm:px-4 py-3 text-center text-xs sm:text-sm text-gray-500">No orders today.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
