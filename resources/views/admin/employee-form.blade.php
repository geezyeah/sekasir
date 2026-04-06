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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">Add Employee</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.employees'), 'label' => 'Employees'],
        ['url' => route('admin.employees.create'), 'label' => 'Create']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-2xl mx-auto px-3 sm:px-4 lg:px-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6">
                <form method="POST" action="{{ route('admin.employees.store') }}">
                    @csrf

                    <div class="mb-4 sm:mb-5">
                        <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5 sm:mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base">
                        @error('name') <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 sm:mb-5">
                        <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5 sm:mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base">
                        @error('email') <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 sm:mb-5">
                        <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5 sm:mb-2">Role</label>
                        <select name="role" class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base">
                            <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 sm:mb-5">
                        <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5 sm:mb-2">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base">
                        @error('password') <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6 sm:mb-8">
                        <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5 sm:mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base">
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                        <a href="{{ route('admin.employees') }}" class="px-4 py-2.5 sm:py-2 text-xs sm:text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors text-center order-2 sm:order-1">Cancel</a>
                        <button type="submit" class="px-4 py-2.5 sm:py-2 text-xs sm:text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors order-1 sm:order-2">Create Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
