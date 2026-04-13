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
                <i class="fas fa-chart-line text-indigo-300"></i>
                {{ __('admin.shift_performance_title') }}
            </h2>
            <a href="{{ route('admin.shifts') }}" class="text-sm text-white hover:text-indigo-200 transition-colors inline-flex items-center gap-2 bg-white bg-opacity-10 px-4 py-2 rounded-lg hover:bg-opacity-20">
                <i class="fas fa-table"></i> {{ __('admin.table_view') }}
            </a>
        </div>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.shifts.summary'), 'label' => 'Shift Summary']
    ]" />

    <div class="py-6 sm:py-12" style="background-color: #242f6d;">
        <div class="max-w-full sm:max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            {{-- Overall Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1">
                                <i class="fas fa-clock text-indigo-600"></i> {{ __('admin.total_shifts') }}
                            </p>
                            <p class="text-3xl font-bold text-indigo-600">{{ $shifts->total() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1">
                                <i class="fas fa-play-circle text-green-600"></i> {{ __('admin.active_now') }}
                            </p>
                            <p class="text-3xl font-bold text-green-600">{{ $activeShifts }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1">
                                <i class="fas fa-shopping-cart text-orange-600"></i> {{ __('admin.orders') }}
                            </p>
                            <p class="text-3xl font-bold text-orange-600">{{ $totalOrders }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1">
                                <i class="fas fa-money-bill-wave text-green-600"></i> {{ __('admin.revenue') }}
                            </p>
                            <p class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shifts List --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden">
                    @forelse($shifts as $shift)
                        <div class="border-b border-gray-200 last:border-b-0 p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-bold text-base text-gray-900">{{ $shift->user->name }}</h4>
                                    <p class="text-xs text-gray-600 mt-1 flex items-center gap-1">
                                        <i class="fas {{ $shift->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $shift->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i>
                                        {{ $shift->shop->name }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $shift->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas {{ $shift->status === 'active' ? 'fa-play-circle' : 'fa-pause-circle' }} mr-1"></i>
                                    {{ ucfirst($shift->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div class="p-2 bg-blue-50 rounded-lg">
                                    <p class="text-xs text-blue-600 font-semibold">Login</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $shift->login_time?->format('H:i') ?? '-' }}</p>
                                </div>
                                <div class="p-2 bg-red-50 rounded-lg">
                                    <p class="text-xs text-red-600 font-semibold">Logout</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $shift->logout_time?->format('H:i') ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <div class="p-2 bg-purple-50 rounded-lg text-center">
                                    <p class="text-xs text-purple-600 font-semibold">Orders</p>
                                    <p class="text-lg font-bold text-purple-600">{{ $shift->orders_count }}</p>
                                </div>
                                <div class="p-2 bg-orange-50 rounded-lg text-center">
                                    <p class="text-xs text-orange-600 font-semibold">Revenue</p>
                                    <p class="text-xs font-bold text-orange-600">Rp {{ number_format($shift->orders->sum('total_amount'), 0, ',', '.') }}</p>
                                </div>
                                <div class="p-2 bg-green-50 rounded-lg text-center">
                                    <p class="text-xs text-green-600 font-semibold">Avg Order</p>
                                    <p class="text-xs font-bold text-green-600">
                                        @if($shift->orders_count > 0)
                                            Rp {{ number_format($shift->orders->sum('total_amount') / $shift->orders_count, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-gray-500 text-sm"><i class="fas fa-chart-line mb-2 block text-3xl opacity-30"></i>{{ __('admin.no_shifts_found') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-user mr-2 text-indigo-600"></i>Employee
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-store mr-2 text-indigo-600"></i>Shop
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-sign-in-alt mr-2 text-indigo-600"></i>Login
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-sign-out-alt mr-2 text-indigo-600"></i>Logout
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-shopping-cart mr-2 text-indigo-600"></i>Orders
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-money-bill mr-2 text-indigo-600"></i>Revenue
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>Avg Order
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-heartbeat mr-2 text-indigo-600"></i>Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($shifts as $shift)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $shift->user->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs rounded-full font-bold inline-flex items-center gap-1">
                                            <i class="fas {{ $shift->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}"></i>
                                            {{ $shift->shop->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-sm font-bold rounded-lg">
                                            {{ $shift->login_time?->format('H:i') ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-red-100 text-red-700 text-sm font-bold rounded-lg">
                                            {{ $shift->logout_time?->format('H:i') ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-purple-100 text-purple-700 text-sm font-bold rounded-lg inline-flex items-center gap-1">
                                            <i class="fas fa-shopping-bag"></i>{{ $shift->orders_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-green-100 text-green-700 text-sm font-bold rounded-lg">
                                            Rp {{ number_format($shift->orders->sum('total_amount'), 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        @if($shift->orders_count > 0)
                                            Rp {{ number_format($shift->orders->sum('total_amount') / $shift->orders_count, 0, ',', '.') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full inline-flex items-center gap-1 {{ $shift->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            <i class="fas {{ $shift->status === 'active' ? 'fa-play-circle' : 'fa-pause-circle' }}"></i>
                                            {{ ucfirst($shift->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-chart-line text-3xl mb-2 block opacity-30"></i>
                                        <span class="text-sm">No shifts found</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-4 bg-gray-50 border-t border-gray-200 text-xs sm:text-sm">
                    {{ $shifts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
