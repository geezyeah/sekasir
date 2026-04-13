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
        @keyframes pulse-glow {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
        }
        .animate-pulse {
            animation: pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <div x-data="shiftManager()">
        <x-slot name="header">
        <div class="flex flex-col gap-3 sm:gap-0 sm:flex-row sm:justify-between sm:items-center">
            <h2 class="font-bold text-2xl sm:text-3xl text-white leading-tight flex items-center gap-2">
                <i class="fas fa-clock text-indigo-300"></i>
                {{ __('admin.employee_shifts') }}
            </h2>
            <a href="{{ route('admin.shifts.summary') }}" class="text-sm text-white hover:text-indigo-200 transition-colors inline-flex items-center gap-2 bg-white bg-opacity-10 px-4 py-2 rounded-lg hover:bg-opacity-20">
                <i class="fas fa-chart-line"></i> {{ __('admin.performance_summary') }}
            </a>
        </div>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.shifts'), 'label' => 'Shifts']
    ]" />

    <div class="py-6 sm:py-12" style="background-color: #242f6d;">
        <div class="max-w-full sm:max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-md p-4 flex items-center gap-3">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600">Active Shifts</p>
                        <p class="text-2xl font-bold text-green-600">{{ $shifts->where('status', 'active')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 flex items-center gap-3">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600">Total Shifts</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $shifts->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 flex items-center gap-3">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i class="fas fa-shopping-cart text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600">Total Orders</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $shifts->sum('orders_count') }}</p>
                    </div>
                </div>
            </div>

            {{-- Currently Active Workers Section --}}
            @if($shifts->where('status', 'active')->count() > 0)
            <div class="mb-8">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-indigo-300"></i>
                    Currently Working ({{ $shifts->where('status', 'active')->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($shifts->where('status', 'active') as $shift)
                    <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-green-500 hover:shadow-xl transition-shadow">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $shift->user->name }}</h4>
                                <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                    <i class="fas {{ $shift->shop->name === 'Ice Lepen' ? 'fa-ice-cream text-red-500' : 'fa-bowl-food text-orange-500' }}"></i>
                                    {{ $shift->shop->name }}
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full flex items-center gap-1">
                                <i class="fas fa-circle animate-pulse"></i>
                                Active
                            </span>
                        </div>

                        <!-- Time Info -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-xs text-blue-600 font-semibold mb-1">Started At</p>
                                <p class="text-sm font-bold text-gray-900">{{ $shift->login_time->format('H:i') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $shift->login_time->format('d M Y') }}</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-3">
                                <p class="text-xs text-purple-600 font-semibold mb-1">Working for</p>
                                <p class="text-sm font-bold text-gray-900">
                                    @php
                                        $duration = now()->diffInMinutes($shift->login_time);
                                        $hours = intdiv($duration, 60);
                                        $minutes = $duration % 60;
                                        echo $hours . 'h ' . $minutes . 'm';
                                    @endphp
                                </p>
                            </div>
                        </div>

                        <!-- Orders Info -->
                        <button @click="openOrdersModal({{ $shift->id }}, '{{ $shift->user->name }}', '{{ $shift->shop->name }}')" class="w-full bg-orange-50 hover:bg-orange-100 rounded-lg p-3 mb-4 text-center transition-colors cursor-pointer border-2 border-transparent hover:border-orange-400">
                            <p class="text-xs text-orange-600 font-semibold mb-1">Orders Processed</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $shift->orders_count }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($shift->orders_count > 0)
                                    <i class="fas fa-shopping-cart mr-1"></i>{{ $shift->orders_count }} {{ $shift->orders_count === 1 ? 'order' : 'orders' }}
                                    <i class="fas fa-search ml-2 text-orange-600"></i>
                                @else
                                    No orders yet
                                @endif
                            </p>
                        </button>

                        <!-- Status Badge -->
                        <div class="flex gap-2 pt-3 border-t border-gray-200">
                            <span class="flex-1 inline-flex items-center justify-center gap-2 text-xs font-semibold text-green-600">
                                <i class="fas fa-check-circle"></i>
                                ON DUTY
                            </span>
                            <button @click="confirmEndShift({{ $shift->id }}, '{{ $shift->user->name }}')" class="flex-1 px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold rounded-lg transition-colors inline-flex items-center justify-center gap-1">
                                <i class="fas fa-stop-circle"></i>
                                End
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- All Shifts Table Section --}}
            <div>
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-list text-indigo-300"></i>
                    All Shifts
                </h3>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden">
                    @forelse($shifts as $shift)
                        <div class="border-b border-gray-200 last:border-b-0 p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-base text-gray-900">{{ $shift->user->name }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <i class="fas fa-store mr-1"></i>{{ $shift->shop->name }}
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

                            <div class="p-2 bg-orange-50 rounded-lg text-center">
                                <p class="text-xs text-orange-600 font-semibold">Orders</p>
                                <button @click="openOrdersModal({{ $shift->id }}, '{{ $shift->user->name }}', '{{ $shift->shop->name }}')" class="text-lg font-bold text-orange-600 hover:text-orange-700 transition-colors cursor-pointer w-full">
                                    {{ $shift->orders_count }}
                                    <i class="fas fa-search text-xs ml-1"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-gray-500 text-sm"><i class="fas fa-clock mb-2 block text-3xl opacity-30"></i>{{ __('admin.no_shifts_found') }}</p>
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
                                    <i class="fas fa-heartbeat mr-2 text-indigo-600"></i>Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-cogs mr-2 text-indigo-600"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($shifts as $shift)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $shift->user->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs rounded-full font-bold">
                                            <i class="fas {{ $shift->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }} mr-1"></i>
                                            {{ $shift->shop->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-sm font-bold rounded-lg">
                                            <i class="fas fa-clock mr-1"></i>{{ $shift->login_time?->format('H:i') ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-red-100 text-red-700 text-sm font-bold rounded-lg">
                                            <i class="fas fa-clock mr-1"></i>{{ $shift->logout_time?->format('H:i') ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button @click="openOrdersModal({{ $shift->id }}, '{{ $shift->user->name }}', '{{ $shift->shop->name }}')" class="px-3 py-1.5 bg-orange-100 hover:bg-orange-200 text-orange-700 text-sm font-bold rounded-lg transition-colors cursor-pointer inline-flex items-center gap-1">
                                            <i class="fas fa-shopping-bag mr-1"></i>{{ $shift->orders_count }}
                                            <i class="fas fa-search text-xs ml-1"></i>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full inline-flex items-center gap-1 {{ $shift->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            <i class="fas {{ $shift->status === 'active' ? 'fa-play-circle' : 'fa-pause-circle' }}"></i>
                                            {{ ucfirst($shift->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($shift->status === 'active')
                                            <button @click="confirmEndShift({{ $shift->id }}, '{{ $shift->user->name }}')" class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-bold rounded-lg transition-colors inline-flex items-center gap-1">
                                                <i class="fas fa-stop-circle"></i>End Shift
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-clock text-3xl mb-2 block opacity-30"></i>
                                        <span class="text-sm">No shifts found</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 bg-gray-50 border-t border-gray-200 text-xs sm:text-sm">
                    {{ $shifts->links() }}
                </div>
                </div>
            </div>
        </div>

        {{-- Orders Modal --}}
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="display: none;">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold mb-1">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            <span x-text="selectedShiftName"></span>
                        </h3>
                        <p class="text-sm text-indigo-100" x-text="selectedShopName"></p>
                    </div>
                    <button @click="showModal = false" class="text-indigo-100 hover:text-white transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div x-show="orders.length > 0">
                        <div class="space-y-3">
                            <template x-for="order in orders" :key="order.id">
                                <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-indigo-400 hover:shadow-md transition-all">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-bold text-gray-900">#<span x-text="order.id"></span></h4>
                                            <p class="text-xs text-gray-500 mt-1" x-text="order.created_at"></p>
                                        </div>
                                        <span class="px-3 py-1 text-xs rounded-full font-semibold" :class="order.payment_type === 'QRIS' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'" x-text="order.payment_type"></span>
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                        <div class="space-y-2 text-sm">
                                            <template x-for="item in order.items" :key="item.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-gray-700"><span x-text="item.quantity"></span>x <span x-text="item.product_name"></span></span>
                                                    <span class="font-semibold text-gray-900">Rp <span x-text="formatCurrency(item.subtotal)"></span></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                        <span class="font-semibold text-gray-900">Total Amount:</span>
                                        <span class="text-lg font-bold text-indigo-600">Rp <span x-text="formatCurrency(order.total_amount)"></span></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Summary -->
                        <div class="mt-6 pt-6 border-t-2 border-gray-200">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-blue-600 font-semibold mb-1">Total Orders</p>
                                    <p class="text-2xl font-bold text-blue-600" x-text="orders.length"></p>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-orange-600 font-semibold mb-1">Total Items</p>
                                    <p class="text-2xl font-bold text-orange-600" x-text="getTotalItems()"></p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-green-600 font-semibold mb-1">Grand Total</p>
                                    <p class="text-xl font-bold text-green-600">Rp <span x-text="formatCurrency(getTotalRevenue())"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div x-show="orders.length === 0" class="text-center py-12">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4 block"></i>
                        <p class="text-gray-500 text-lg">No orders found for this shift</p>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="text-center py-12">
                        <div class="inline-block">
                            <i class="fas fa-spinner fa-spin text-3xl text-indigo-600 mb-4 block"></i>
                            <p class="text-gray-600">Loading orders...</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 border-t border-gray-200 p-4 flex justify-end gap-3">
                    <button @click="showModal = false" class="px-4 py-2 text-gray-700 border-2 border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>

        {{-- End Shift Confirmation Modal --}}
        <div x-show="showConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="display: none;">
            <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full p-6">
                <!-- Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                    </div>
                </div>

                <!-- Title & Message -->
                <h3 class="text-xl font-bold text-center text-gray-900 mb-2">
                    End Shift Confirmation
                </h3>
                <p class="text-center text-gray-600 mb-6">
                    Are you sure you want to forcefully end the shift for <strong x-text="confirmingShiftName"></strong>? This action cannot be undone.
                </p>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button @click="showConfirmModal = false" :disabled="endingShift" class="flex-1 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Cancel
                    </button>
                    <button @click="endShift()" :disabled="endingShift" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                        <i class="fas fa-stop-circle"></i>
                        <span x-show="!endingShift">End Shift</span>
                        <span x-show="endingShift">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function shiftManager() {
            return {
                showModal: false,
                selectedShiftId: null,
                selectedShiftName: '',
                selectedShopName: '',
                orders: [],
                loading: false,
                showConfirmModal: false,
                confirmingShiftId: null,
                confirmingShiftName: null,
                endingShift: false,

                openOrdersModal(shiftId, employeeName, shopName) {
                    this.selectedShiftId = shiftId;
                    this.selectedShiftName = employeeName;
                    this.selectedShopName = shopName;
                    this.showModal = true;
                    this.loadOrders();
                },

                confirmEndShift(shiftId, employeeName) {
                    this.confirmingShiftId = shiftId;
                    this.confirmingShiftName = employeeName;
                    this.showConfirmModal = true;
                },

                async endShift() {
                    this.endingShift = true;
                    try {
                        const response = await fetch(`/admin/shifts/${this.confirmingShiftId}/end`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.showConfirmModal = false;
                            // Reload page to show updated shifts
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        } else {
                            alert('Error ending shift: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error ending shift:', error);
                        alert('Error ending shift: ' + error.message);
                    } finally {
                        this.endingShift = false;
                    }
                },

                async loadOrders() {
                    this.loading = true;
                    try {
                        const response = await fetch(`/api/shifts/${this.selectedShiftId}/orders`);
                        const data = await response.json();
                        this.orders = data.orders || [];
                    } catch (error) {
                        console.error('Error loading orders:', error);
                        this.orders = [];
                    } finally {
                        this.loading = false;
                    }
                },

                getTotalItems() {
                    return this.orders.reduce((sum, order) => {
                        return sum + order.items.reduce((itemSum, item) => itemSum + parseInt(item.quantity || 0), 0);
                    }, 0);
                },

                getTotalRevenue() {
                    return this.orders.reduce((sum, order) => sum + parseFloat(order.total_amount || 0), 0);
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(value);
                }
            }
        }
    </script>
</x-app-layout>
