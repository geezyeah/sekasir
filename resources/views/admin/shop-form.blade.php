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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">
                Edit Shop: <i class="fas {{ $shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }}; margin-right: 8px;"></i>{{ $shop->name }}
            </h2>
            <a href="{{ route('admin.shops') }}" class="text-indigo-300 hover:text-indigo-100 transition-colors">← Back</a>
        </div>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.shops'), 'label' => 'Shops'],
        ['url' => route('admin.shops.edit', $shop), 'label' => 'Edit: ' . $shop->name]
    ]" />

    <div class="py-12" style="background-color: #242f6d;">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.shops.update', $shop) }}" class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Shop Logo</label>
                        <div class="space-y-3">
                            @if($shop->getProperty('logo_path'))
                                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <img src="{{ $shop->getProperty('logo_path') }}" alt="{{ $shop->name }} logo" class="h-16 max-w-[120px] object-contain">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Current Logo</p>
                                        <p class="text-xs text-gray-500">{{ $shop->getProperty('logo_path') }}</p>
                                    </div>
                                </div>
                            @endif
                            <input type="hidden" id="logo_path" name="logo_path" value="{{ $shop->getProperty('logo_path', '') }}">
                            <p class="text-xs text-gray-500">Note: Upload a logo through the file system or enter the path directly</p>
                            <input type="text" id="logo_path_input" placeholder="/images/logos/your-logo.png" value="{{ $shop->getProperty('logo_path', '') }}" class="w-full text-sm px-3 py-2 rounded border border-gray-300 focus:outline-none focus:border-indigo-500">
                            <p class="text-xs text-gray-500">Enter the full path to your logo image (e.g., /images/logos/ice-lepen-logo.png)</p>
                        </div>
                        @error('logo_path')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bg_color" class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" id="bg_color" name="bg_color" value="{{ $shop->getProperty('bg_color', '#ffffff') }}" class="w-16 h-16 rounded border border-gray-300 cursor-pointer">
                            <div>
                                <input type="text" value="{{ $shop->getProperty('bg_color', '#ffffff') }}" readonly class="text-sm bg-gray-100 px-3 py-2 rounded border border-gray-200 font-mono w-full max-w-xs">
                                <p class="text-xs text-gray-500 mt-1">The background color of the shop POS page</p>
                            </div>
                        </div>
                        @error('bg_color')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="text_color" class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" id="text_color" name="text_color" value="{{ $shop->getProperty('text_color', '#1f2937') }}" class="w-16 h-16 rounded border border-gray-300 cursor-pointer">
                            <div>
                                <input type="text" value="{{ $shop->getProperty('text_color', '#1f2937') }}" readonly class="text-sm bg-gray-100 px-3 py-2 rounded border border-gray-200 font-mono w-full max-w-xs">
                                <p class="text-xs text-gray-500 mt-1">The text color for readability on the background</p>
                            </div>
                        </div>
                        @error('text_color')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Primary/Accent Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" id="primary_color" name="primary_color" value="{{ $shop->getProperty('primary_color', '#4f46e5') }}" class="w-16 h-16 rounded border border-gray-300 cursor-pointer">
                            <div>
                                <input type="text" value="{{ $shop->getProperty('primary_color', '#4f46e5') }}" readonly class="text-sm bg-gray-100 px-3 py-2 rounded border border-gray-200 font-mono w-full max-w-xs">
                                <p class="text-xs text-gray-500 mt-1">Primary color for buttons and accents</p>
                            </div>
                        </div>
                        @error('primary_color')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Preview --}}
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Preview</h3>
                        <div class="rounded-lg overflow-hidden shadow-sm" style="background-color: {{ $shop->getProperty('bg_color', '#ffffff') }}; color: {{ $shop->getProperty('text_color', '#1f2937') }}; padding: 2rem;">
                            <h4 class="text-2xl font-bold mb-2">{{ $shop->name }}</h4>
                            <p class="mb-4">This is how the POS page will look with these colors.</p>
                            <button type="button" class="px-4 py-2 rounded-lg font-semibold" style="background-color: {{ $shop->getProperty('primary_color', '#4f46e5') }}; color: {{ $shop->getProperty('text_color', '#1f2937') }};">
                                Sample Button
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.shops') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update preview in real-time
        const bgColorInput = document.getElementById('bg_color');
        const textColorInput = document.getElementById('text_color');
        const primaryColorInput = document.getElementById('primary_color');
        const logoPathInput = document.getElementById('logo_path_input');
        const logoPathHidden = document.getElementById('logo_path');
        const preview = document.querySelector('[style*="background-color"]');

        [bgColorInput, textColorInput, primaryColorInput].forEach(input => {
            input.addEventListener('change', function() {
                const bgColor = bgColorInput.value;
                const textColor = textColorInput.value;
                const primaryColor = primaryColorInput.value;
                
                preview.style.backgroundColor = bgColor;
                preview.style.color = textColor;
                preview.querySelector('button').style.backgroundColor = primaryColor;
            });
        });

        // Update logo path on form submission
        document.querySelector('form').addEventListener('submit', function() {
            logoPathHidden.value = logoPathInput.value;
        });
    </script>
</x-app-layout>
