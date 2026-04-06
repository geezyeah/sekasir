<x-app-layout>
    @php
        $bgColor = '#242f6d';
        $headerTextColor = 'text-white';
        
        if (!$isAdmin && $activeShop) {
            $bgColor = $activeShop->getProperty('bg_color', '#242f6d');
            $headerTextColor = 'text-white';
        }
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
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl sm:text-3xl text-white leading-tight flex items-center">
                    <svg class="w-7 h-7 mr-2 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Shift History
                </h2>
                <p class="text-sm text-gray-100 mt-1">View and manage your work shifts</p>
            </div>
        </div>
    </x-slot>

    <script type="application/json" id="shiftsData">
        @json($shifts->toArray())
    </script>

    <script>
        function shiftDetails() {
            return {
                showDetails: false,
                selectedShift: {},
                shiftsData: {},

                selectShift(shiftId) {
                    this.selectedShift = this.shiftsData[shiftId];
                    this.showDetails = true;
                },

                getEmployeeName() {
                    if (this.selectedShift?.user?.name) return this.selectedShift.user.name;
                    if (this.selectedShift?.user) return this.selectedShift.user;
                    return '-';
                },

                formatRupiah(amount) {
                    return 'Rp ' + Number(amount).toLocaleString('id-ID');
                },

                calculateDuration(shift) {
                    if (!shift?.login_time || !shift?.logout_time) {
                        if (shift?.login_time) {
                            const start = new Date(shift.login_time);
                            const now = new Date();
                            const diff = Math.floor((now - start) / 1000);
                            const hours = Math.floor(diff / 3600);
                            const minutes = Math.floor((diff % 3600) / 60);
                            return hours + 'h ' + minutes + 'm (ongoing)';
                        }
                        return '-';
                    }
                    const start = new Date(shift.login_time);
                    const end = new Date(shift.logout_time);
                    const diff = Math.floor((end - start) / 1000);
                    const hours = Math.floor(diff / 3600);
                    const minutes = Math.floor((diff % 3600) / 60);
                    return hours + 'h ' + minutes + 'm';
                },

                calculateTotalRevenue(shift) {
                    if (!shift?.orders || shift.orders.length === 0) return 0;
                    return shift.orders.reduce((total, order) => {
                        const amount = order.total_amount || order.amount || order.grand_total || 0;
                        return total + (Number(amount) || 0);
                    }, 0);
                },

                init() {
                    try {
                        const shiftsJson = document.getElementById('shiftsData').textContent.trim();
                        const response = JSON.parse(shiftsJson);
                        
                        // Handle both direct array and paginated response
                        const shifts = Array.isArray(response) ? response : response.data;
                        
                        if (Array.isArray(shifts)) {
                            shifts.forEach(shift => {
                                this.shiftsData[shift.id] = shift;
                            });
                        }
                    } catch(e) {
                        console.error('Error loading shifts:', e);
                    }
                }
            };
        }
    </script>

    <div class="py-2 sm:py-12" style="min-height: 460px; background-color: {{ $bgColor }};" x-data="shiftDetails()" x-init="init()" >
        <div class="max-w-full sm:max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            {{-- Summary Statistics --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 sm:gap-4 mb-4 sm:mb-6">
                <div class="bg-white rounded-lg shadow-lg p-3 sm:p-4 border-l-4 border-blue-500">
                    <p class="text-xs sm:text-sm text-gray-600">Total Shifts</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-900">{{ $shifts->total() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-3 sm:p-4 border-l-4 border-purple-500">
                    <p class="text-xs sm:text-sm text-gray-600">Active Shifts</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-900">{{ $shifts->where('status', 'active')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-3 sm:p-4 border-l-4 border-orange-500">
                    <p class="text-xs sm:text-sm text-gray-600">Total Orders</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-900">{{ $shifts->sum(fn($s) => $s->orders->count()) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-2 p-2 sm:p-4">
                    @forelse($shifts as $shift)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-base"><i class="fas {{ $shift->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $shift->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i></span>
                                    <div>
                                        <span class="font-semibold text-xs sm:text-sm text-gray-900">{{ $shift->shop->name }}</span>
                                        <p class="text-xs text-gray-500">{{ $shift->user->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $shift->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} font-semibold">
                                    {{ ucfirst($shift->status) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <p class="text-gray-500">Login</p>
                                    <p class="font-semibold text-gray-900">{{ $shift->login_time?->format('d M H:i') ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Logout</p>
                                    <p class="font-semibold text-gray-900">{{ $shift->logout_time?->format('d M H:i') ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 border-t border-gray-200 pt-2 text-xs">
                                <div>
                                    <p class="text-gray-500">Duration</p>
                                    @if($shift->login_time && $shift->logout_time)
                                        <p class="font-semibold text-gray-900">{{ $shift->login_time->diffForHumans($shift->logout_time, true) }}</p>
                                    @elseif($shift->login_time)
                                        <p class="font-semibold text-gray-900">{{ $shift->login_time->diffForHumans(now(), true) }}</p>
                                    @else
                                        <p class="font-semibold text-gray-900">-</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-gray-500">Orders</p>
                                    <p class="font-semibold text-gray-900">{{ $shift->orders->count() }}</p>
                                </div>
                            </div>
                            <button @click="selectShift({{ $shift->id }})"
                                    class="w-full mt-2 px-3 py-2 bg-blue-500 text-white text-xs font-semibold rounded hover:bg-blue-600 transition">
                                View Details
                            </button>
                        </div>
                    @empty
                        <p class="text-center text-xs sm:text-sm text-gray-500 py-4">No shift history found.</p>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shop</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Login</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logout</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($shifts as $shift)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1 sm:gap-2">
                                            <span class="text-base sm:text-lg"><i class="fas {{ $shift->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $shift->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i></span>
                                            <span class="text-xs sm:text-sm font-medium text-gray-900">{{ $shift->shop->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900">
                                        {{ $shift->user->name ?? '-' }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                        {{ $shift->login_time?->format('d M Y H:i') ?? '-' }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                        {{ $shift->logout_time?->format('d M Y H:i') ?? '-' }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                        @if($shift->login_time && $shift->logout_time)
                                            {{ $shift->login_time->diffForHumans($shift->logout_time, true) }}
                                        @elseif($shift->login_time)
                                            {{ $shift->login_time->diffForHumans(now(), true) }} (ongoing)
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                        {{ $shift->orders->count() }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        @if($shift->status === 'active')
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <button @click="selectShift({{ $shift->id }})"
                                                class="px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded hover:bg-blue-600 transition">
                                            Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 sm:px-6 py-4 text-center text-xs sm:text-sm text-gray-500">No shift history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-2 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $shifts->links() }}
                </div>
            </div>
        </div>

        {{-- Shift Details Modal --}}
        <div x-show="showDetails" x-cloak x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-gray-50 px-4 sm:px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Shift Details</h3>
                <button @click="showDetails = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="px-4 sm:px-6 py-4 space-y-4">
                {{-- Shop, Employee & Status --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Shop</p>
                        <p class="text-base font-semibold text-gray-900" x-text="selectedShift?.shop?.name || '-'"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Employee</p>
                        <p class="text-base font-semibold text-gray-900" x-text="getEmployeeName()"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p x-show="selectedShift?.status === 'active'" class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</p>
                        <p x-show="selectedShift?.status !== 'active'" class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</p>
                    </div>
                </div>

                {{-- Time Information --}}
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Time Information</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Login Time</p>
                            <p class="text-sm font-semibold text-gray-900" x-text="selectedShift?.login_time ? new Date(selectedShift.login_time).toLocaleString() : '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Logout Time</p>
                            <p class="text-sm font-semibold text-gray-900" x-text="selectedShift?.logout_time ? new Date(selectedShift.logout_time).toLocaleString() : '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Duration</p>
                            <p class="text-sm font-semibold text-gray-900" x-text="calculateDuration(selectedShift)"></p>
                        </div>
                    </div>
                </div>

                {{-- Sales & Orders --}}
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Sales Summary</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Total Orders</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="selectedShift?.orders?.length || 0"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Total Revenue</p>
                            <p class="text-2xl font-bold text-green-600" x-text="formatRupiah(calculateTotalRevenue(selectedShift))"></p>
                        </div>
                    </div>
                </div>

                {{-- Orders List --}}
                <template x-if="selectedShift?.orders && selectedShift.orders.length > 0">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Orders</h4>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            <template x-for="order in selectedShift.orders" :key="order.id">
                                <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900" x-text="'Order #' + order.id"></p>
                                            <p class="text-xs text-gray-500" x-text="new Date(order.created_at).toLocaleString()"></p>
                                        </div>
                                        <p class="text-sm font-bold text-gray-900" x-text="formatRupiah(order.total_amount)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div class="sticky bottom-0 bg-gray-50 px-4 sm:px-6 py-3 border-t border-gray-200 flex justify-end">
                <button @click="showDetails = false" class="px-4 py-2 bg-gray-300 text-gray-900 font-semibold rounded hover:bg-gray-400 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
