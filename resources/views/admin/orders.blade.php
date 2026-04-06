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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">Orders</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.orders'), 'label' => 'Orders']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-full sm:max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            {{-- Filters --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-6 mb-3 sm:mb-6">
                <form method="GET" action="{{ route('admin.orders') }}" class="flex flex-col sm:flex-row flex-wrap gap-1.5 sm:gap-4 items-end">
                    <div class="flex-1 min-w-fit">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Shop</label>
                        <select name="shop_id" class="w-full px-2 sm:px-3 py-1 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-base">
                            <option value="">All Shops</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-fit">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="w-full px-2 sm:px-3 py-1 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-base">
                    </div>
                    <div class="flex gap-1">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 sm:px-4 py-1 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors">Filter</button>
                        <a href="{{ route('admin.orders') }}" class="text-xs sm:text-sm text-gray-500 hover:text-gray-700 px-2 py-1 sm:py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Reset</a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-2 p-2 sm:p-4">
                    @forelse($orders as $order)
                        <div class="p-2 bg-gray-50 rounded-lg border border-gray-200 space-y-1">
                            <div class="flex justify-between items-start">
                                <p class="font-semibold text-xs sm:text-sm text-gray-900">Order #{{ $order->id }}</p>
                                <span class="px-1.5 py-0.5 text-xs rounded-full {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $order->payment_type }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600">{{ $order->user->name }} • {{ $order->shop->name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                            <p class="text-xs text-gray-600">Items: {{ $order->items->sum('quantity') }}</p>
                            <p class="font-semibold text-xs sm:text-sm text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-500 py-4">No orders found</p>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Shop</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Payment</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-medium text-gray-900">{{ $order->id }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-500">{{ Str::limit($order->user->name, 8) }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-500 hidden sm:table-cell"><i class="fas {{ $order->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $order->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i></td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-500">
                                        {{ $order->items->sum('quantity') }}x
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 hidden lg:table-cell">
                                        <span class="px-1.5 py-0.5 sm:px-2 sm:py-1 text-xs rounded-full {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $order->payment_type }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-500 hidden md:table-cell">{{ $order->created_at->format('d M H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-2 sm:px-4 py-3 text-center text-xs sm:text-sm text-gray-500">No orders found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-2 sm:p-4 text-xs sm:text-sm border-t border-gray-200">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
