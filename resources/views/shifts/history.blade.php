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
        <div class="flex flex-col gap-3 sm:gap-0 sm:flex-row sm:justify-between sm:items-start">
            <div>
                <h2 class="font-bold text-2xl sm:text-3xl text-white leading-tight flex items-center gap-2">
                    <i class="fas fa-history text-indigo-300"></i>
                    My Shift History
                </h2>
                <p class="text-sm text-gray-200 mt-2">View and track your work shifts and performance</p>
            </div>
            @if($isAdmin)
                <a href="{{ route('admin.shifts') }}" class="text-sm text-white hover:text-indigo-200 transition-colors inline-flex items-center gap-2 bg-white bg-opacity-10 px-4 py-2 rounded-lg hover:bg-opacity-20">
                    <i class="fas fa-users mr-1"></i> Team Monitoring
                </a>
            @endif
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
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-clock text-indigo-600"></i> TOTAL SHIFTS</p>
                            <p class="text-3xl font-bold text-indigo-600">{{ $shifts->total() }}</p>
                        </div>
                        <i class="fas fa-history text-4xl text-indigo-100"></i>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-play-circle text-green-600"></i> ACTIVE SHIFTS</p>
                            <p class="text-3xl font-bold text-green-600">{{ $shifts->where('status', 'active')->count() }}</p>
                        </div>
                        <i class="fas fa-play text-4xl text-green-100"></i>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1 flex items-center gap-1"><i class="fas fa-shopping-cart text-orange-600"></i> TOTAL ORDERS</p>
                            <p class="text-3xl font-bold text-orange-600">{{ $shifts->sum(fn($s) => $s->orders->count()) }}</p>
                        </div>
                        <i class="fas fa-shopping-bag text-4xl text-orange-100"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden">
                    @forelse($shifts as $shift)
                        <div class="border-b border-gray-200 last:border-b-0 p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-bold text-base text-gray-900 flex items-center gap-2">
                                        <i class="fas {{ $shift->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $shift->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i>
                                        {{ $shift->shop->name }}
                                    </h4>
                                    <p class="text-xs text-gray-600 mt-1">{{ $shift->created_at->format('d M Y') }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $shift->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas {{ $shift->status === 'active' ? 'fa-play-circle' : 'fa-pause-circle' }} mr-1"></i>
                                    {{ ucfirst($shift->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <div class="p-2 bg-blue-50 rounded-lg">
                                    <p class="text-xs text-blue-600 font-semibold">Login</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $shift->login_time?->format('H:i') ?? '-' }}</p>
                                </div>
                                <div class="p-2 bg-red-50 rounded-lg">
                                    <p class="text-xs text-red-600 font-semibold">Logout</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $shift->logout_time?->format('H:i') ?? '-' }}</p>
                                </div>
                                <div class="p-2 bg-orange-50 rounded-lg">
                                    <p class="text-xs text-orange-600 font-semibold">Orders</p>
                                    <p class="text-sm font-bold text-orange-600">{{ $shift->orders->count() }}</p>
                                </div>
                            </div>

                            <div class="p-2 bg-gray-50 rounded-lg mb-3">
                                <p class="text-xs text-gray-600 font-semibold">Duration</p>
                                @if($shift->login_time && $shift->logout_time)
                                    <p class="text-sm font-bold text-gray-900">{{ $shift->login_time->diffForHumans($shift->logout_time, true) }}</p>
                                @elseif($shift->login_time)
                                    <p class="text-sm font-bold text-green-600">{{ $shift->login_time->diffForHumans(now(), true) }} (ongoing)</p>
                                @else
                                    <p class="text-sm font-bold text-gray-900">-</p>
                                @endif
                            </div>

                            <button @click="selectShift({{ $shift->id }})" class="w-full px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </button>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-gray-500 text-sm"><i class="fas fa-clock mb-2 block text-3xl opacity-30"></i>No shift history found</p>
                        </div>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 border-b-2 border-gray-300">
                            <tr>
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
                                    <i class="fas fa-hourglass-half mr-2 text-indigo-600"></i>Duration
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-shopping-cart mr-2 text-indigo-600"></i>Orders
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-heartbeat mr-2 text-indigo-600"></i>Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($shifts as $shift)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 text-sm font-bold rounded-lg inline-flex items-center gap-1">
                                            <i class="fas {{ $shift->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}"></i>
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
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        @if($shift->login_time && $shift->logout_time)
                                            {{ $shift->login_time->diffForHumans($shift->logout_time, true) }}
                                        @elseif($shift->login_time)
                                            <span class="text-green-600">{{ $shift->login_time->diffForHumans(now(), true) }} (ongoing)</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 bg-orange-100 text-orange-700 text-sm font-bold rounded-lg">
                                            <i class="fas fa-shopping-bag mr-1"></i>{{ $shift->orders->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full inline-flex items-center gap-1 {{ $shift->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            <i class="fas {{ $shift->status === 'active' ? 'fa-play-circle' : 'fa-pause-circle' }}"></i>
                                            {{ ucfirst($shift->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button @click="selectShift({{ $shift->id }})" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-1"></i>Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-clock text-3xl mb-2 block opacity-30"></i>
                                        <span class="text-sm">No shift history found</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 bg-gray-50 border-t border-gray-200 text-xs sm:text-sm">
                    {{ $shifts->links() }}
                </div>
            </div>
        </div>

        {{-- Shift Details Modal --}}
        <div x-show="showDetails" x-cloak x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-indigo-600 px-6 py-4 border-b border-indigo-700 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Shift Details
                    </h3>
                    <button @click="showDetails = false" class="text-white hover:text-indigo-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="px-6 py-6 space-y-6">
                    {{-- Shop, Employee & Status --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                            <p class="text-xs font-bold text-indigo-700 mb-1 flex items-center gap-1"><i class="fas fa-store"></i> SHOP</p>
                            <p class="text-base font-semibold text-gray-900" x-text="selectedShift?.shop?.name || '-'"></p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-xs font-bold text-blue-700 mb-1 flex items-center gap-1"><i class="fas fa-user"></i> EMPLOYEE</p>
                            <p class="text-base font-semibold text-gray-900" x-text="getEmployeeName()"></p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-xs font-bold text-green-700 mb-1 flex items-center gap-1"><i class="fas fa-heartbeat"></i> STATUS</p>
                            <span x-show="selectedShift?.status === 'active'" class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-play-circle mr-1"></i>Active
                            </span>
                            <span x-show="selectedShift?.status !== 'active'" class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">
                                <i class="fas fa-pause-circle mr-1"></i>Inactive
                            </span>
                        </div>
                    </div>

                    {{-- Time Information --}}
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-clock text-indigo-600"></i>Time Information
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-blue-700 font-bold mb-1">Login Time</p>
                                <p class="text-sm font-semibold text-gray-900" x-text="selectedShift?.login_time ? new Date(selectedShift.login_time).toLocaleString() : '-'"></p>
                            </div>
                            <div class="p-3 bg-red-50 rounded-lg">
                                <p class="text-xs text-red-700 font-bold mb-1">Logout Time</p>
                                <p class="text-sm font-semibold text-gray-900" x-text="selectedShift?.logout_time ? new Date(selectedShift.logout_time).toLocaleString() : '-'"></p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded-lg col-span-2">
                                <p class="text-xs text-orange-700 font-bold mb-1">Duration</p>
                                <p class="text-lg font-bold text-orange-600" x-text="calculateDuration(selectedShift)"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Orders & Revenue --}}
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-indigo-600"></i>Performance Summary
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-purple-50 rounded-lg border border-purple-200">
                                <p class="text-xs text-purple-700 font-bold mb-1">Total Orders</p>
                                <p class="text-2xl font-bold text-purple-600" x-text="selectedShift?.orders?.length || 0"></p>
                            </div>
                            <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                                <p class="text-xs text-green-700 font-bold mb-1">Total Revenue</p>
                                <p class="text-lg font-bold text-green-600" x-text="formatRupiah(calculateTotalRevenue(selectedShift))"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Orders List --}}
                    <template x-if="selectedShift?.orders && selectedShift.orders.length > 0">
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-shopping-cart text-indigo-600"></i>Orders (<span x-text="selectedShift.orders.length"></span>)
                            </h4>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                <template x-for="order in selectedShift.orders" :key="order.id">
                                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                                    <i class="fas fa-receipt text-indigo-600"></i>Order #<span x-text="order.id"></span>
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1" x-text="new Date(order.created_at).toLocaleString()"></p>
                                            </div>
                                            <p class="text-lg font-bold text-green-600" x-text="formatRupiah(order.total_amount)"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="sticky bottom-0 bg-indigo-50 px-6 py-4 border-t border-indigo-200 flex justify-end">
                    <button @click="showDetails = false" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-check"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
