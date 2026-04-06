<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2 sm:py-12">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 sm:gap-4 lg:gap-6">
                {{-- Welcome Card --}}
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 sm:p-6 text-gray-900">
                        <h3 class="text-base sm:text-2xl font-bold mb-1 sm:mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                        <p class="text-xs sm:text-base text-gray-600">
                            {{ __("You're logged in to the POS system. Ready to start your shift!") }}
                        </p>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 sm:p-6">
                        <h3 class="text-xs sm:text-sm font-semibold text-gray-500 uppercase mb-2 sm:mb-4">Quick Access</h3>
                        <div class="space-y-1 sm:space-y-2">
                            <a href="{{ route('pos.index') }}" class="block p-2 sm:p-3 bg-indigo-50 text-indigo-700 rounded-lg text-xs sm:text-sm font-medium hover:bg-indigo-100 transition">
                                → Go to POS
                            </a>
                            <a href="{{ route('shifts.history') }}" class="block p-2 sm:p-3 bg-blue-50 text-blue-700 rounded-lg text-xs sm:text-sm font-medium hover:bg-blue-100 transition">
                                → Shift History
                            </a>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block p-2 sm:p-3 bg-purple-50 text-purple-700 rounded-lg text-xs sm:text-sm font-medium hover:bg-purple-100 transition">
                                    → Admin Panel
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
