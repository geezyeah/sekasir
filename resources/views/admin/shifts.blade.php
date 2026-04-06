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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">Shifts</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.shifts'), 'label' => 'Shifts']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-full sm:max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Shop</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Login</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Logout</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($shifts as $shift)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">{{ Str::limit($shift->user->name, 10) }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                        {{ $shift->shop->name === 'Ice Lepen' ? '🍦' : '🥟' }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-500 hidden md:table-cell">{{ $shift->login_time?->format('d M H:i') ?? '-' }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-500 hidden lg:table-cell">{{ $shift->logout_time?->format('d M H:i') ?? '-' }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-500">{{ $shift->orders_count }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4">
                                        <span class="px-1.5 py-0.5 sm:px-2 sm:py-1 text-xs rounded-full {{ $shift->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($shift->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm text-gray-500">No shifts found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-2 sm:p-4 text-xs sm:text-sm">
                    {{ $shifts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
