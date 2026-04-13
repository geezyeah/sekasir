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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">{{ __('admin.orders_title') }}</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.orders'), 'label' => 'Orders']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-full sm:max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            {{-- Filters --}}
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-6">
                <form method="GET" action="{{ route('admin.orders') }}" class="space-y-3 sm:space-y-0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Shop</label>
                            <select name="shop_id" class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-colors">
                                <option value="">All Shops</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-colors">
                        </div>
                        <div class="flex gap-2 items-end pt-2 sm:pt-0">
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow-md transition-all hover:shadow-lg transform hover:scale-105">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                            <a href="{{ route('admin.orders') }}" class="flex-1 text-center text-sm text-gray-700 hover:text-gray-900 px-4 py-2.5 border-2 border-gray-300 rounded-lg hover:bg-gray-100 font-semibold transition-colors">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden">
                    @forelse($orders as $order)
                        <div class="border-b border-gray-200 last:border-b-0 p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-bold text-base text-gray-900">Order #{{ $order->id }}</h3>
                                        <span class="px-2.5 py-1 text-xs rounded-full font-semibold {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                            {{ $order->payment_type }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-1">{{ $order->created_at->format('d M Y • H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-lg text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-2 bg-gray-50 rounded-lg p-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 font-medium">Employee:</span>
                                    <span class="text-gray-900 font-semibold">{{ $order->user->name }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 font-medium">Shop:</span>
                                    <span class="text-gray-900 font-semibold flex items-center gap-1.5">
                                        <i class="fas {{ $order->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $order->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i>
                                        {{ $order->shop->name }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 font-medium">Items:</span>
                                    <span class="text-gray-900 font-semibold">{{ $order->items->sum('quantity') }}x</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('orders.show', $order) }}" class="mt-3 block text-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 py-1.5 px-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </a>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-gray-500 text-sm"><i class="fas fa-inbox mb-2 block text-3xl opacity-30"></i>{{ __('admin.no_orders_found') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">#ID</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Employee</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Shop</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Items</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Payment</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Date & Time</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($orders as $order)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $order->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fas {{ $order->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $order->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i>
                                            <span class="text-gray-700">{{ $order->shop->name }}</span>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $order->items->sum('quantity') }}x</td>
                                    <td class="px-4 py-3 text-sm font-bold text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $order->payment_type === 'QRIS' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                            {{ $order->payment_type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->created_at->format('d M Y • H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('orders.show', $order) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 px-3 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors inline-flex items-center gap-1">
                                            <i class="fas fa-eye"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-2 block opacity-30"></i>
                                        <span class="text-sm">{{ __('admin.no_orders_found') }}</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 text-sm border-t border-gray-200 bg-gray-50">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
