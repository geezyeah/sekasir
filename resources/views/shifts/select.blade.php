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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">
            Select Shop
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12" style="background-color: #242f6d;">
        <div class="max-w-full lg:max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <div class="font-bold mb-2">{{ __('Error') }}</div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>{{ session('warning') }}</div>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zm-1 3a1 1 0 100-2 1 1 0 000 2zm7 1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                    </svg>
                    <div>{{ session('info') }}</div>
                </div>
            @endif

            <!-- Welcome Section -->
            <div class="text-center mb-8 sm:mb-12">
                <h1 class="text-2xl sm:text-4xl font-bold text-white mb-2">Welcome, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-sm sm:text-lg text-gray-200">Select a shop to start your shift and begin work</p>
                @if($activeShift)
                    <p class="text-sm text-yellow-100 mt-3 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        You have an active shift at {{ $activeShift->shop->name }}
                    </p>
                @endif
            </div>

            <!-- Shop Cards Grid -->
            @if($shops->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                @foreach($shops as $shop)
                    @php
                        $bgColor = $shop->getProperty('bg_color', '#8d140c');
                        $textColor = $shop->getProperty('text_color', '#f2dec5');
                        $isActiveShop = $activeShift && $activeShift->shop->id === $shop->id;
                    @endphp
                    
                    @if($isActiveShop)
                        {{-- Active Shift Card --}}
                        <div class="group">
                            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border-t-4 p-6 sm:p-8 h-full" style="border-color: {{ $bgColor }}; border: 2px solid {{ $bgColor }};">
                                <!-- Shop Logo -->
                                <div class="mb-4 text-center">
                                    @php
                                        $logoPath = $shop->getProperty('logo_path', null);
                                    @endphp
                                    @if($logoPath)
                                        <img src="{{ $logoPath }}" alt="{{ $shop->name }}" style="max-width: 120px; height: auto; margin: 0 auto; min-height:75px;" class="object-contain">
                                    @else
                                        <div class="text-5xl sm:text-6xl">
                                            @if($shop->name === 'Ice Lepen')
                                                🍦
                                            @else
                                                🥟
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Shop Name & Description -->
                                <div class="text-center mb-6">
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">{{ $shop->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        @if($shop->name === 'Ice Lepen')
                                            Ice Cream Shop
                                        @else
                                            Dimsum Shop
                                        @endif
                                    </p>
                                </div>

                                <!-- Active Shift Status Badge -->
                                <div class="mb-6 p-3 rounded-lg text-center font-semibold text-white" style="background: linear-gradient(to right, {{ $bgColor }}, {{ $bgColor }}dd));">
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                        </svg>
                                        <span>{{ __('pos.active_shift') }}</span>
                                    </div>
                                </div>

                                <!-- Shift Info -->
                                <div class="space-y-3 mb-6 p-4 rounded-lg" style="background-color: {{ $bgColor }}15;">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">Started:</span>
                                        <span class="font-semibold" style="color: {{ $bgColor }};">{{ $activeShift->login_time->format('H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">Duration:</span>
                                        <span class="font-semibold duration-timer" id="duration-{{ $activeShift->id }}" data-start-time="{{ $activeShift->login_time->timestamp * 1000 }}" style="color: {{ $bgColor }};">00:00:00</span>
                                    </div>
                                </div>

                                <!-- End Shift Button -->
                                <form method="POST" action="{{ route('shifts.end') }}" onsubmit="return confirm('Are you sure you want to end this shift?');">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2.5 sm:py-3 rounded-lg font-semibold text-white text-sm sm:text-base transition-all duration-300 flex items-center justify-center gap-2 hover:shadow-lg hover:opacity-90" style="background-color: {{ $bgColor }};">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        End Shift
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Normal Shop Card --}}
                        <form method="POST" action="{{ route('shifts.start') }}" class="group">
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                            
                            <div class="bg-white overflow-hidden shadow-md rounded-2xl hover:shadow-2xl transition-all duration-300 h-full border-t-4 p-6 sm:p-8" style="border-color: {{ $bgColor }};">
                                <!-- Shop Logo -->
                                <div class="mb-4 text-center">
                                    @php
                                        $logoPath = $shop->getProperty('logo_path', null);
                                    @endphp
                                    @if($logoPath)
                                        <img src="{{ $logoPath }}" alt="{{ $shop->name }}" style="max-width: 120px; height: auto; margin: 0 auto; min-height:75px;" class="object-contain">
                                    @else
                                        <div class="text-5xl sm:text-6xl transform group-hover:scale-110 transition-transform duration-300">
                                            @if($shop->name === 'Ice Lepen')
                                                🍦
                                            @else
                                                🥟
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Shop Name & Description -->
                                <div class="text-center mb-6">
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">{{ $shop->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        @if($shop->name === 'Ice Lepen')
                                            Ice Cream Shop
                                        @else
                                            Dimsum Shop
                                        @endif
                                    </p>
                                </div>

                                <!-- Shop Status -->
                                <div class="mb-6 p-3 rounded-lg border text-center" style="border-color: {{ $bgColor }}; background-color: rgba(255, 255, 255, 0.5);">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="inline-block w-2 h-2 rounded-full" style="background-color: {{ $bgColor }};"></span>
                                        <span class="text-xs sm:text-sm font-medium" style="color: {{ $bgColor }};">{{ __('pos.active') }}</span>
                                    </div>
                                </div>

                                <!-- Start Shift Button -->
                                <button type="submit" class="w-full px-4 py-2.5 sm:py-3 rounded-lg font-semibold text-white text-sm sm:text-base transition-all duration-300 flex items-center justify-center gap-2 hover:shadow-lg" style="background-color: {{ $bgColor }};">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Start Shift
                                </button>
                            </div>
                        </form>
                    @endif
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Authorized Shops</h3>
                    <p class="text-gray-600 mb-6">You don't have access to any shops yet. Please contact the administrator to grant you access.</p>
                    <a href="{{ route('dashboard') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Go to Dashboard
                    </a>
                </div>
            </div>
            @endif

            <!-- Duration Timer Script -->
            <script>
                function formatDuration(seconds) {
                    const hours = Math.floor(seconds / 3600);
                    const minutes = Math.floor((seconds % 3600) / 60);
                    const secs = seconds % 60;
                    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                }

                function updateDuration() {
                    const timers = document.querySelectorAll('.duration-timer');
                    timers.forEach(timer => {
                        const startTime = parseInt(timer.getAttribute('data-start-time'));
                        const now = Date.now();
                        const elapsedSeconds = Math.floor((now - startTime) / 1000);
                        timer.textContent = formatDuration(elapsedSeconds);
                    });
                }

                // Update immediately
                updateDuration();
                
                // Then update every second
                setInterval(updateDuration, 1000);
            </script>

            <!-- Additional Info -->
            <div class="mt-12 sm:mt-16 text-center">
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200 inline-block">
                    <p class="text-xs sm:text-sm text-gray-600">
                        <svg class="w-4 h-4 inline mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                        </svg>
                        Kenapa kau kerjakan sekarang, jika kau bisa mengerjakannya besok.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
