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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">Manage Shop Authorization</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.employees'), 'label' => 'Employees'],
        ['url' => '', 'label' => $user->name]
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-2xl mx-auto px-2 sm:px-4 lg:px-8">
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
                <div class="p-4 sm:p-6">
                    <!-- Employee Info -->
                    <div class="mb-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-1">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>

                    <!-- Forms -->
                    <form method="POST" action="{{ route('admin.employees.shops.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Shop Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">Authorized Shops</label>
                            <p class="text-xs sm:text-sm text-gray-600 mb-4">Select which shops this employee can work at:</p>

                            <div class="space-y-3">
                                @forelse($allShops as $shop)
                                    <div class="flex items-center p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <input
                                            type="checkbox"
                                            id="shop_{{ $shop->id }}"
                                            name="shops[]"
                                            value="{{ $shop->id }}"
                                            {{ in_array($shop->id, $authorizedShops) ? 'checked' : '' }}
                                            class="rounded text-indigo-600 w-4 h-4"
                                        >
                                        <label for="shop_{{ $shop->id }}" class="ml-3 flex-1 cursor-pointer">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-sm sm:text-base text-gray-900">{{ $shop->name }}</span>
                                                @php
                                                    $logo = $shop->getProperty('logo_path');
                                                @endphp
                                                @if($logo)
                                                    <img src="{{ $logo }}" alt="{{ $shop->name }}" class="w-6 h-6 object-contain">
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 py-4">No shops found</p>
                                @endforelse
                            </div>

                            @error('shops')
                                <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 sm:gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.employees') }}" class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium text-xs sm:text-sm transition-colors text-center">
                                Cancel
                            </a>
                            <button type="submit" class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs sm:text-sm transition-colors">
                                Save Changes
                            </button>
                        </div>
                    </form>

                    <!-- Info Box -->
                    <div class="mt-6 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs sm:text-sm text-blue-800">
                            <strong>Note:</strong> This employee will only be able to select and work at the authorized shops. Admins can access all shops by default.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
