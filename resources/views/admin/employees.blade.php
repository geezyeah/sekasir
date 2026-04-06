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
            <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">Employees</h2>
            <a href="{{ route('admin.employees.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors w-full sm:w-auto text-center">
                + Add Employee
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
                <div class="mb-2 sm:mb-4 p-2 sm:p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs sm:text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-2 p-2 sm:p-4">
                    @forelse($employees as $employee)
                        <div class="p-2 bg-gray-50 rounded-lg border border-gray-200 space-y-2">
                            <div class="flex justify-between items-start">
                                <p class="font-semibold text-xs sm:text-sm text-gray-900">{{ $employee->name }}</p>
                                <span class="px-1.5 py-0.5 text-xs rounded-full {{ $employee->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }} white-space nowrap">
                                    {{ ucfirst($employee->role) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600">📧 {{ Str::limit($employee->email, 20) }}</p>
                            <p class="text-xs text-gray-500">Shifts: <span class="font-semibold">{{ $employee->shifts_count }}</span></p>
                            @if($employee->role === 'employee')
                                <a href="{{ route('admin.employees.shops', $employee) }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">Manage Shops →</a>
                            @endif
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-500 py-4">No employees found</p>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 border-b-2 border-indigo-300">
                            <tr>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Name</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Email</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Role</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Shifts</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-indigo-900 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($employees as $employee)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">{{ $employee->name }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-500">{{ Str::limit($employee->email, 16) }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4">
                                        <span class="px-1.5 py-0.5 sm:px-2 sm:py-1 text-xs rounded-full {{ $employee->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($employee->role) }}
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-500">{{ $employee->shifts_count }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm">
                                        @if($employee->role === 'employee')
                                            <a href="{{ route('admin.employees.shops', $employee) }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Manage Shops</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
