<nav x-data="{ open: false }" 
    style="@if(Auth::user()->isAdmin() || request()->routeIs('shifts.select', 'shifts.history')) background-color: #242f6d; color: white; border-bottom: 1px solid rgba(255, 255, 255, 0.1); @elseif($activeShop ?? false) background-color: {{ $activeShop->getProperty('bg_color', '#ffffff') }}; color: {{ $activeShop->getProperty('text_color', '#1f2937') }}; border-bottom: 1px solid {{ $activeShop->getProperty('text_color', '#1f2937') }}; border-opacity: 0.3; @else background-color: white; color: #1f2937; border-bottom: 1px solid #f3f4f6; @endif"
>
    <div class="max-w-full sm:max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <div class="flex justify-between h-14 sm:h-16">
            <div class="flex items-center gap-2 sm:gap-6">
                <div class="shrink-0 flex items-center">
                    @if($activeShop ?? false)
                        <a href="{{ route('dashboard') }}" class="flex items-center py-1">
                            <img src="{{ $activeShop->getProperty('logo_path', '/images/logos/default-logo.png') }}" alt="{{ $activeShop->name }}" style="max-width: 88px; height: auto;" class="object-contain">
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-base sm:text-xl font-bold text-white truncate">
                            SEKASIR
                        </a>
                    @endif
                </div>

                <div class="hidden space-x-2 sm:space-x-2.5 sm:-my-px sm:ms-4 md:flex">
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-gauge-high text-xs"></i>
                            <span class="hidden lg:inline">Dashboard</span>
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-chart-bar text-xs"></i>
                            <span class="hidden lg:inline">Reports</span>
                        </x-nav-link>
                        <x-nav-link :href="route('admin.employees')" :active="request()->routeIs('admin.employees*')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-users text-xs"></i>
                            <span class="hidden lg:inline">Employees</span>
                        </x-nav-link>
                        <x-nav-link :href="route('admin.products')" :active="request()->routeIs('admin.products*')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-box text-xs"></i>
                            <span class="hidden lg:inline">Products</span>
                        </x-nav-link>
                        <x-nav-link :href="route('admin.product-types')" :active="request()->routeIs('admin.product-types*')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-list text-xs"></i>
                            <span class="hidden lg:inline">Types</span>
                        </x-nav-link>
                        <x-nav-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders*')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-receipt text-xs"></i>
                            <span class="hidden lg:inline">Orders</span>
                        </x-nav-link>
                        <x-nav-link :href="route('admin.shifts')" :active="request()->routeIs('admin.shifts*')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-clock text-xs"></i>
                            <span class="hidden lg:inline">Shifts</span>
                        </x-nav-link>
                        <x-nav-link :href="route('admin.shops')" :active="request()->routeIs('admin.shops*')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-cog text-xs"></i>
                            <span class="hidden lg:inline">Shop Settings</span>
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->activeShift)
                        <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-cash-register text-xs"></i>
                            <span class="hidden lg:inline">POS</span>
                        </x-nav-link>
                        <x-nav-link :href="route('pos.shift-report')" :active="request()->routeIs('pos.shift-report')" class="text-xs sm:text-sm flex items-center gap-0.5">
                            <i class="fas fa-file-alt text-xs"></i>
                            <span class="hidden lg:inline">Shift Report</span>
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('shifts.history')" :active="request()->routeIs('shifts.history')" class="text-xs sm:text-sm flex items-center gap-0.5">
                        <i class="fas fa-history text-xs"></i>
                        <span class="hidden lg:inline">Shift History</span>
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden md:flex md:items-center md:gap-2 lg:gap-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button style="@if(Auth::user()->isAdmin() || request()->routeIs('shifts.select', 'shifts.history')) color: white; background-color: transparent; @elseif($activeShop ?? false) color: {{ $activeShop->getProperty('text_color', '#1f2937') }}; background-color: transparent; @else color: white; background-color: rgba(255, 255, 255, 0.2); @endif" class="inline-flex items-center px-2 sm:px-3 py-2 border border-transparent text-xs sm:text-sm leading-4 font-medium rounded-md hover:opacity-75 focus:outline-none transition ease-in-out duration-150">
                            <div class="truncate max-w-20 sm:max-w-none">{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-xs sm:text-sm flex items-center gap-2">
                            <i class="fas fa-user w-4"></i>
                            Profile
                        </x-dropdown-link>

                        @if(Auth::user()->activeShift)
                            <form method="POST" action="{{ route('shifts.end') }}">
                                @csrf
                                <x-dropdown-link :href="route('shifts.end')"
                                        onclick="event.preventDefault(); this.closest('form').submit();" class="text-xs sm:text-sm flex items-center gap-2">
                                    <i class="fas fa-power-off w-4"></i>
                                    End Shift
                                </x-dropdown-link>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="logout-form-desktop">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="handleLogoutClick(event, this)" class="text-xs sm:text-sm flex items-center gap-2">
                                <i class="fas fa-sign-out-alt w-4"></i>
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-1 md:hidden flex items-center gap-1">
                <!-- @if(Auth::user()->activeShift)
                    <span class="inline-flex items-center px-1.5 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                        {{ Str::limit(Auth::user()->activeShift->shop->name, 6) }}
                    </span>
                @endif -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md transition duration-150 ease-in-out" :style="'@if(Auth::user()->isAdmin() || request()->routeIs('shifts.select', 'shifts.history')) color: rgba(255, 255, 255, 0.6); @else color: #9ca3af; @endif'">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 hover:opacity-80 focus:opacity-80" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden" style="@if(Auth::user()->isAdmin() || request()->routeIs('shifts.select', 'shifts.history')) background-color: #242f6d; color: white; @elseif($activeShop ?? false) background-color: {{ $activeShop->getProperty('bg_color', '#ffffff') }}; color: {{ $activeShop->getProperty('text_color', '#1f2937') }}; @else background-color: #f9fafb; color: #1f2937; @endif">
        <div class="pt-2 pb-3 space-y-1 px-3 border-b-2" style="@if(Auth::user()->isAdmin() || request()->routeIs('shifts.select', 'shifts.history')) border-color: rgba(255, 255, 255, 0.2); @elseif($activeShop ?? false) border-color: {{ $activeShop->getProperty('text_color', '#1f2937') }}; border-opacity: 0.2; @else border-color: #e5e7eb; @endif">
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-gauge-high w-4"></i>
                    Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-chart-bar w-4"></i>
                    Reports
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.employees')" :active="request()->routeIs('admin.employees*')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-users w-4"></i>
                    Employees
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.products')" :active="request()->routeIs('admin.products*')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-box w-4"></i>
                    Products
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.product-types')" :active="request()->routeIs('admin.product-types*')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-list w-4"></i>
                    Types
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders*')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-receipt w-4"></i>
                    Orders
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.shifts')" :active="request()->routeIs('admin.shifts*')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-clock w-4"></i>
                    Shifts
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.shops')" :active="request()->routeIs('admin.shops*')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-cog w-4"></i>
                    Shop Settings
                </x-responsive-nav-link>
            @endif
            @if(Auth::user()->activeShift)
                <x-responsive-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-cash-register w-4"></i>
                    POS
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pos.shift-report')" :active="request()->routeIs('pos.shift-report')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-file-alt w-4"></i>
                    Shift Report
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('shifts.history')" :active="request()->routeIs('shifts.history')" class="text-xs sm:text-sm flex items-center gap-2">
                <i class="fas fa-history w-4"></i>
                Shift History
            </x-responsive-nav-link>
        </div>

        <div class="pt-3 pb-2 px-3" style="@if(Auth::user()->isAdmin() || request()->routeIs('shifts.select', 'shifts.history')) border-top: 2px solid rgba(255, 255, 255, 0.2); @elseif($activeShop ?? false) border-top: 2px solid {{ $activeShop->getProperty('text_color', '#1f2937') }}; border-opacity: 0.3; @else border-top: 1px solid #e5e7eb; @endif">
            <div class="font-medium text-xs sm:text-base truncate" style="@if(Auth::user()->isAdmin() || request()->routeIs('shifts.select', 'shifts.history')) color: white; @elseif($activeShop ?? false) color: {{ $activeShop->getProperty('text_color', '#1f2937') }}; @else color: #1f2937; @endif">{{ Auth::user()->name }}</div>
            <div class="font-medium text-xs" style="@if(Auth::user()->isAdmin() || request()->routeIs('shifts.select', 'shifts.history')) color: rgba(255, 255, 255, 0.8); @elseif($activeShop ?? false) color: {{ $activeShop->getProperty('text_color', '#1f2937') }}; opacity: 0.8; @else color: #6b7280; @endif">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-xs sm:text-sm flex items-center gap-2">
                    <i class="fas fa-user w-4"></i>
                    Profile
                </x-responsive-nav-link>

                @if(Auth::user()->activeShift)
                    <form method="POST" action="{{ route('shifts.end') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('shifts.end')"
                                onclick="event.preventDefault(); this.closest('form').submit();" class="text-xs sm:text-sm flex items-center gap-2">
                            <i class="fas fa-power-off w-4"></i>
                            End Shift
                        </x-responsive-nav-link>
                    </form>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="logout-form-mobile">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="handleLogoutClick(event, this)" class="text-xs sm:text-sm flex items-center gap-2">
                        <i class="fas fa-sign-out-alt w-4"></i>
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    function handleLogoutClick(event, element) {
        event.preventDefault();
        
        @if(Auth::user()->activeShift)
            const hasActiveShift = true;
        @else
            const hasActiveShift = false;
        @endif
        
        if (hasActiveShift) {
            const confirmed = confirm('You have an active shift. Please end the shift first before logging out.\n\nWould you like to end the shift now?');
            if (confirmed) {
                // Create a form to POST to the end shift route
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('shifts.end') }}';
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                 Array.from(document.querySelectorAll('input[name="_token"]'))
                                     .map(el => el.value)[0];
                
                if (csrfToken) {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrfToken;
                    form.appendChild(tokenInput);
                }
                
                document.body.appendChild(form);
                form.submit();
            }
            return;
        }
        
        // Find the logout form - try to get closest form first, then look for logout form by class
        let logoutForm = element?.closest('form');
        if (!logoutForm) {
            // If closest form doesn't work, find by class name
            logoutForm = document.querySelector('.logout-form-desktop') || 
                        document.querySelector('.logout-form-mobile');
        }
        
        if (logoutForm) {
            logoutForm.submit();
        }
    }
</script>
