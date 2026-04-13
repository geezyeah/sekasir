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
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-0">
            <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">{{ __('admin.employees_title') }}</h2>
            <a href="{{ route('admin.employees.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors w-full sm:w-auto text-center">
                + {{ __('admin.add_employee') }}
            </a>
        </div>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.employees'), 'label' => 'Employees']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d; min-height: 450px;">
        <div class="max-w-full sm:max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg text-sm flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden">
                    @forelse($employees as $employee)
                        <div class="border-b border-gray-200 last:border-b-0 p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-base text-gray-900">{{ $employee->name }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">📧 {{ $employee->email }}</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs rounded-full font-semibold whitespace-nowrap {{ $employee->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($employee->role) }}
                                </span>
                            </div>

                            {{-- Shop Assignments --}}
                            @if($employee->role === 'employee')
                                <div class="mb-3 p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                                    <p class="text-xs font-semibold text-indigo-900 mb-2 flex items-center gap-1">
                                        <i class="fas fa-store"></i> Assigned Shops
                                    </p>
                                    @if($employee->authorizedShops && $employee->authorizedShops->count() > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($employee->authorizedShops as $shop)
                                                <span class="px-2 py-1 bg-indigo-600 text-white text-xs rounded-full font-medium">{{ $shop->name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-xs text-indigo-600 italic">No shops assigned</p>
                                    @endif
                                </div>
                            @endif

                            <div class="flex gap-2">
                                @if($employee->role === 'employee')
                                    <a href="{{ route('admin.employees.shops', $employee) }}" class="flex-1 text-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 py-2 px-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <i class="fas fa-cogs mr-1"></i>Manage Shops
                                    </a>
                                @else
                                    <span class="flex-1 text-center text-sm font-semibold text-gray-400 py-2 px-3">Admin User</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-gray-500 text-sm"><i class="fas fa-users mb-2 block text-3xl opacity-30"></i>{{ __('admin.no_employees_found') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Assigned Shops</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Shifts</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($employees as $employee)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $employee->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $employee->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs rounded-full font-bold {{ $employee->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($employee->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($employee->role === 'employee')
                                            @if($employee->authorizedShops && $employee->authorizedShops->count() > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($employee->authorizedShops as $shop)
                                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs rounded-full font-semibold">
                                                            <i class="fas fa-store mr-1"></i>{{ $shop->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-500 italic">No shops assigned</span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">All Shops</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                            <i class="fas fa-calendar mr-1"></i>{{ $employee->shifts_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            @if($employee->role === 'employee')
                                                <a href="{{ route('admin.employees.shops', $employee) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 px-3 py-1.5 rounded hover:bg-indigo-50 transition-colors">
                                                    <i class="fas fa-cogs mr-1"></i>Manage Shops
                                                </a>
                                            @else
                                                <span class="text-sm font-semibold text-gray-400">Admin</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-users text-3xl mb-2 block opacity-30"></i>
                                        <span class="text-sm">No employees found</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
