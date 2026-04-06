<x-app-layout>
    <x-slot name="header">
        <!-- Mobile Header -->
        <div class="md:hidden">
            <div class="flex justify-between items-start gap-2 mb-3">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">{{ $shop->name }}</h2>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="inline-block w-2 h-2 rounded-full" style="background-color: #219d38;"></span>
                        <span class="text-xs font-semibold" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Active</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('pos.shift-report') }}" class="text-white hover:opacity-75 transition-colors" title="View shift report">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </a>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="text-white hover:opacity-75 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <form method="POST" action="{{ route('shifts.end') }}" class="w-full">
                                @csrf
                                <x-dropdown-link :href="route('shifts.end')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-xs">End Shift</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
            
            <!-- Mobile Info Cards - Single Row -->
            <div class="grid grid-cols-3 gap-2">
                <div class="bg-white bg-opacity-10 rounded-lg p-2 backdrop-blur">
                    <p class="text-xs opacity-75" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Started</p>
                    <p class="text-xs font-bold mt-1" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">{{ $shift->login_time->format('H:i') }}</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-2 backdrop-blur">
                    <p class="text-xs opacity-75" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Duration</p>
                    <p class="text-xs font-bold mt-1" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};" id="shift-duration-mobile">--:--:--</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-2 backdrop-blur">
                    <p class="text-xs opacity-75" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Now</p>
                    <p class="text-xs font-bold mt-1" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};" id="shift-current-time-mobile">--:--</p>
                </div>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:flex justify-between items-start gap-8">
            <!-- Left: Shop Info -->
            <div class="flex-shrink-0 pt-1">
                <h2 class="text-3xl font-bold mb-2" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">{{ $shop->name }}</h2>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color: #219d38;"></span>
                    <span class="text-sm font-semibold" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Active Shift</span>
                </div>
            </div>
            
            <!-- Center: Shift Details Cards -->
            <div class="flex-1 grid grid-cols-2 gap-4">
                <div class="bg-white bg-opacity-10 rounded-xl p-4 backdrop-blur">
                    <p class="text-xs opacity-75" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Shift Started</p>
                    <p class="text-lg font-bold mt-2" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">{{ $shift->login_time->format('d M Y') }}</p>
                    <p class="text-sm opacity-75 mt-1" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">{{ $shift->login_time->format('H:i') }}</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-xl p-4 backdrop-blur">
                    <p class="text-xs opacity-75" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Shift Duration</p>
                    <p class="text-lg font-bold mt-2" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};" id="shift-duration-header">--:--:--</p>
                    <p class="text-sm opacity-75 mt-1" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Current Time: <span id="shift-current-time">--:--</span></p>
                </div>
            </div>
            
            <!-- Right: Actions & Employee Info -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-lg px-5 py-3 border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center gap-4 text-sm font-medium text-gray-700">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color: {{ $shop->getProperty('bg_color', '#8d140c') }};">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-500 text-xs font-semibold">Employee:</span>
                            <span class="font-bold text-gray-900" style="padding-left:5px;">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="w-px h-4 bg-gray-200"></div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color: {{ $shop->getProperty('bg_color', '#8d140c') }};">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-500 text-xs font-semibold">Shift Time:</span>
                            <span class="font-bold text-gray-900" id="shift-timer" style="padding-left:5px;">{{ $shift->login_time->format('H:i') }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('pos.shift-report') }}" class="text-white hover:opacity-75 transition-colors" title="View shift report">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </a>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="text-white hover:opacity-75 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('shifts.end') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                    onclick="return confirm('End your shift? Cart will be cleared.')">
                                End Shift
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </x-slot>

    <div class="py-2 md:py-6" x-data="posApp()" x-init="loadCart()">
        <div class="max-w-7xl mx-auto px-2 md:px-4 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 pb-20 md:pb-0">
                {{-- Cart (Desktop Sidebar) --}}
                <div class="md:col-span-1 order-1 md:order-2 md:sticky md:top-20 hidden md:block">
                    <div class="rounded-xl md:rounded-2xl shadow-lg p-3 md:p-6 lg:sticky lg:top-6" style="background-color: white;">
                        <div class="flex justify-between items-center mb-3 md:mb-5">
                            <h3 class="text-base md:text-lg font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Cart</h3>
                            <button @click="clearCart()" x-show="Object.keys(cart).length > 0"
                                    class="text-xs md:text-sm font-semibold transition-colors hover:opacity-70" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                                Clear
                            </button>
                        </div>

                        <template x-if="Object.keys(cart).length === 0">
                            <div class="text-center py-4 md:py-8">
                                <div class="text-2xl md:text-4xl mb-1 md:mb-2"><i class="fas fa-shopping-cart" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" opacity="0.6;"></i></div>
                                <p class="text-xs md:text-sm" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; opacity: 0.6;">Cart is empty</p>
                            </div>
                        </template>

                        <div class="space-y-2 max-h-40 md:max-h-96 overflow-y-auto">
                            <template x-for="(item, cartId) in cart" :key="cartId">
                                <div class="flex items-center justify-between p-2 md:p-3 rounded-lg transition-all duration-200" style="background-color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs md:text-sm font-semibold truncate" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="item.product_name"></p>
                                        <p class="text-xs" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; opacity: 0.6;" x-text="formatRupiah(item.price) + ' each'"></p>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button @click="updateQty(cartId, item.quantity - 1)"
                                                class="w-5 h-5 md:w-6 md:h-6 flex items-center justify-center rounded-full text-xs font-bold transition-colors"
                                                style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                            −
                                        </button>
                                        <span class="text-xs font-bold w-4 text-center" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="item.quantity"></span>
                                        <button @click="updateQty(cartId, item.quantity + 1)"
                                                class="w-5 h-5 md:w-6 md:h-6 flex items-center justify-center rounded-full text-xs font-bold transition-colors" 
                                                style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                            +
                                        </button>
                                        <button @click="removeItem(cartId)"
                                                class="flex items-center justify-center text-xs md:text-sm transition-colors ml-1" 
                                                style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="Object.keys(cart).length > 0" class="mt-3 md:mt-5 pt-3 md:pt-5" style="border-top: 2px solid {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                            <div class="flex justify-between items-center mb-2 md:mb-4">
                                <span class="text-sm md:text-base font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Total</span>
                                <span class="text-base md:text-xl font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="formatRupiah(cartTotal)"></span>
                            </div>

                            <button @click="showPayment = true"
                                    class="w-full font-bold py-2 md:py-3 px-4 rounded-lg transition-all duration-200 text-xs md:text-base shadow-lg hover:shadow-xl transform hover:scale-105" 
                                    style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                Submit Order
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Mobile Fixed Bottom Cart --}}
                <div class="md:hidden fixed bottom-0 left-0 right-0 z-40" style="background-color: white; box-shadow: 0 -2px 10px rgba(0,0,0,0.1);">
                    {{-- Collapsed Cart Bar --}}
                    <button @click="cartCollapsed = !cartCollapsed" 
                            class="w-full px-4 py-3 flex items-center justify-between" 
                            style="border-top: 2px solid {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <span class="text-sm font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                                Cart (<span x-text="getTotalQuantity()"></span>)
                            </span>
                            <span class="text-xs text-gray-600" x-show="Object.keys(cart).length > 0" x-text="'Total ' + formatRupiah(cartTotal)"></span>
                        </div>
                        <svg class="w-5 h-5 transition-transform" :class="!cartCollapsed ? 'rotate-180' : ''" 
                             style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7-7m0 0L5 14m7-7v12"></path>
                        </svg>
                    </button>

                    {{-- Expanded Cart Content --}}
                    <div x-show="!cartCollapsed" x-transition class="max-h-[calc(100vh-180px)] overflow-y-auto bg-white">
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-base font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Cart Details</h3>
                                <button @click="clearCart()" x-show="Object.keys(cart).length > 0"
                                        class="text-xs font-semibold transition-colors hover:opacity-70" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                                    Clear
                                </button>
                            </div>

                            <template x-if="Object.keys(cart).length === 0">
                                <div class="text-center py-6">
                                <div class="text-2xl mb-1"><i class="fas fa-shopping-cart" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" opacity="0.6;"></i></div>
                            </template>

                            <div class="space-y-2 mb-4">
                                <template x-for="(item, cartId) in cart" :key="cartId">
                                    <div class="flex items-center justify-between p-2 rounded-lg transition-all duration-200" style="background-color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold truncate" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="item.product_name"></p>
                                            <p class="text-xs" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; opacity: 0.6;" x-text="formatRupiah(item.price) + ' each'"></p>
                                            <p class="text-xs font-semibold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="'Subtotal: ' + formatRupiah(item.price * item.quantity)"></p>
                                        </div>
                                        <div class="flex items-center gap-1 ml-2">
                                            <button @click="updateQty(cartId, item.quantity - 1)"
                                                    class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold transition-colors"
                                                    style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                                −
                                            </button>
                                            <span class="text-xs font-bold w-4 text-center" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="item.quantity"></span>
                                            <button @click="updateQty(cartId, item.quantity + 1)"
                                                    class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold transition-colors" 
                                                    style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                                +
                                            </button>
                                            <button @click="removeItem(cartId)"
                                                    class="flex items-center justify-center text-xs transition-colors ml-1" 
                                                    style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div x-show="Object.keys(cart).length > 0" class="pt-3 border-t-2" style="border-color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Total</span>
                                    <span class="text-base font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="formatRupiah(cartTotal)"></span>
                                </div>

                                <button @click="showPayment = true"
                                        class="w-full font-bold py-2.5 px-4 rounded-lg transition-all duration-200 text-sm shadow-lg hover:shadow-xl" 
                                        style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                    Submit Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product List --}}
                <div class="md:col-span-2 order-2 md:order-1">
                    <div class="rounded-xl md:rounded-2xl shadow-lg p-3 md:p-6" style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                        <h3 class="text-base md:text-lg font-bold mb-3 md:mb-5" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Products</h3>
                        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1.5 md:gap-3">
                            @foreach($products as $product)
                                <button
                                    @click="addToCart({{ $product->id }})"
                                    class="flex flex-col items-center p-1.5 md:p-3 rounded-lg md:rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg border-2 group"
                                    style="background-color: {{ $shop->getProperty('text_color', '#F5E6D3') }}; border-color: rgba(255,255,255,0.3); hover:background-color: white;"
                                >
                                    <div class="text-xl md:text-3xl mb-0.5 flex items-center justify-center">
                                        @if($shop->name === 'Ice Lepen')
                                            @if($product->productType && str_contains(strtolower($product->productType->name), 'cone'))
                                                <i class="fas fa-ice-cream" style="color: #c41e3a;"></i>
                                            @else
                                                <i class="fas fa-ice-cream" style="color: #c41e3a;"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-bowl-food" style="color: #f39c12;"></i>
                                        @endif
                                    </div>
                                    <span class="text-xs md:text-sm font-semibold text-center leading-tight line-clamp-2" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">{{ $product->name }}</span>
                                    <span class="text-xs md:text-sm font-bold mt-0.5 md:mt-1" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @if($product->is_seasonal)
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-1 py-0.5 rounded-full mt-0.5">Seasonal</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Order History for this shift --}}
                    <div class="rounded-xl md:rounded-2xl shadow-lg p-3 md:p-6 mt-2 md:mt-6" style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                        <h3 class="text-sm md:text-lg font-bold mb-2 md:mb-4" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Shift Orders</h3>
                        <div id="order-history">
                            <template x-if="orders.length === 0">
                                <p class="text-xs md:text-sm" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }}; opacity: 0.7;">No orders yet.</p>
                            </template>
                            <div class="space-y-1.5 md:space-y-2 max-h-40 md:max-h-96 overflow-y-auto">
                                <template x-for="order in orders" :key="order.id">
                                    <a :href="'/orders/' + order.id" class="flex justify-between items-center p-2 md:p-3 rounded-lg md:rounded-xl transition-all duration-200 cursor-pointer transform hover:scale-102 hover:shadow-md" style="background-color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                                        <div>
                                            <span class="text-xs md:text-sm font-semibold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="order.formatted_id"></span>
                                            <span class="text-xs ml-1 md:ml-2" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; opacity: 0.6;" x-text="new Date(order.created_at).toLocaleTimeString()"></span>
                                        </div>
                                        <div class="flex items-center gap-1 md:gap-2">
                                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                                  :class="order.payment_type === 'QRIS' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700'"
                                                  x-text="order.payment_type"></span>
                                            <span class="text-xs md:text-sm font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="formatRupiah(order.total_amount)"></span>
                                            <span style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" class="ml-1">→</span>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Modal --}}
        <div x-show="showPayment" x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50 p-4"
             @keydown.escape.window="showPayment = false">
            <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl w-full sm:max-w-md max-h-[90vh] overflow-y-auto p-5" @click.outside="showPayment = false">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-lg font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Payment</h3>
                    <button @click="showPayment = false" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
                </div>

                <div class="text-center mb-4 p-2.5 rounded-xl" style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                    <p class="text-xs" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }}; opacity: 0.8;">Total Amount</p>
                    <p class="text-2xl font-bold" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};" x-text="formatRupiah(cartTotal)"></p>
                </div>

                {{-- Payment Type Selection --}}
                <div class="grid grid-cols-2 gap-2.5 mb-3">
                    <button @click="paymentType = 'QRIS'; cashReceived = ''"
                            :class="paymentType === 'QRIS' ? 'text-white border-2' : 'bg-white text-gray-700 border-2 border-gray-200 hover:border-gray-300'"
                            :style="paymentType === 'QRIS' ? 'background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; border-color: {{ $shop->getProperty('bg_color', '#A31F1F') }};' : ''"
                            class="py-2.5 px-3 rounded-lg border font-semibold transition-all text-center">
                        <div class="text-lg mb-0.5">
                            <i class="fas fa-qrcode" :style="paymentType === 'QRIS' ? 'color: white;' : 'color: #3b82f6;'"></i>
                        </div>
                        <span class="text-xs">QRIS</span>
                    </button>
                    <button @click="paymentType = 'CASH'"
                            :class="paymentType === 'CASH' ? 'text-white border-2' : 'bg-white text-gray-700 border-2 border-gray-200 hover:border-gray-300'"
                            :style="paymentType === 'CASH' ? 'background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; border-color: {{ $shop->getProperty('bg_color', '#A31F1F') }};' : ''"
                            class="py-2.5 px-3 rounded-lg border font-semibold transition-all text-center">
                        <div class="text-lg mb-0.5">
                            <i class="fas fa-money-bill-wave" :style="paymentType === 'CASH' ? 'color: white;' : 'color: #10b981;'"></i>
                        </div>
                        <span class="text-xs">CASH</span>
                    </button>
                </div>

                {{-- CASH Input --}}
                <div x-show="paymentType === 'CASH'" x-transition class="mb-3">
                    <label class="block text-xs font-medium mb-1.5" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Cash Received</label>
                    <div class="relative mb-2.5">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 font-medium" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Rp</span>
                        <input type="text" x-model="cashReceived" readonly
                               class="w-full pl-10 pr-4 py-2 text-right text-lg font-bold border-2 rounded-lg" style="border-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; background-color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                    </div>

                    {{-- Quick Amount Buttons --}}
                    <div class="grid grid-cols-1 gap-1.5 mb-2.5">
                        <button @click="cashReceived = String(cartTotal)" class="py-1.5 rounded-lg text-xs font-medium transition-colors" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }}; background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; border: 1px solid {{ $shop->getProperty('bg_color', '#A31F1F') }};">Exact</button>
                    </div>

                    {{-- Numeric Keypad --}}
                    <div class="grid grid-cols-3 gap-1.5 mb-2.5">
                        <template x-for="num in ['1','2','3','4','5','6','7','8','9','000','0','⌫']" :key="num">
                            <button @click="handleKeypad(num)"
                                    :class="num === '⌫' ? '' : ''"
                                    class="py-1.5 rounded-lg font-bold text-sm transition-colors"
                                    :style="num === '⌫' ? 'background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};' : num === '000' ? 'background-color: {{ $shop->getProperty('bg_color', '#FFD700') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};' : 'background-color: {{ $shop->getProperty('text_color', '#F5E6D3') }}; color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; border: 1px solid {{ $shop->getProperty('bg_color', '#A31F1F') }};'">
                                <span x-text="num"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Change Amount --}}
                    <div x-show="cashReceived && Number(cashReceived) >= cartTotal" class="mb-2.5 p-2.5 rounded-lg" style="background-color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Change</span>
                            <span class="text-base font-bold" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="formatRupiah(Number(cashReceived) - cartTotal)"></span>
                        </div>
                    </div>
                    <div x-show="cashReceived && Number(cashReceived) < cartTotal" class="mb-2.5 p-2.5 rounded-lg" style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">Insufficient</span>
                            <span class="text-base font-bold" style="color: {{ $shop->getProperty('text_color', '#F5E6D3') }};" x-text="formatRupiah(cartTotal - Number(cashReceived))"></span>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button @click="submitOrder()"
                        :disabled="processing || (paymentType === 'CASH' && (!cashReceived || Number(cashReceived) < cartTotal)) || !paymentType"
                        :class="processing || (paymentType === 'CASH' && (!cashReceived || Number(cashReceived) < cartTotal)) || !paymentType ? 'cursor-not-allowed opacity-50' : 'hover:shadow-lg transform hover:scale-105'"
                        :style="processing || (paymentType === 'CASH' && (!cashReceived || Number(cashReceived) < cartTotal)) || !paymentType ? 'background-color: #ccc;' : 'background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};'"
                        class="w-full font-bold py-2.5 px-4 rounded-lg transition-all text-sm shadow-md">
                    <span x-show="!processing">Confirm Payment</span>
                    <span x-show="processing">Processing...</span>
                </button>
            </div>
        </div>

        {{-- Success Modal --}}
        <div x-show="showSuccess" x-cloak x-transition.duration.300ms
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center" :style="'border-top: 4px solid ' + '{{ $shop->getProperty('bg_color', '#A31F1F') }}'">
                <div class="text-5xl mb-3"><i class="fas fa-check-circle" style="color: #10b981;"></i></div>
                <h3 class="text-xl font-bold mb-1" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};">Order Success!</h3>
                <p class="text-sm mb-1" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; opacity: 0.7;" x-text="'Order #' + (lastOrder?.id || '')"></p>
                <p class="text-lg font-bold mb-2" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="formatRupiah(lastOrder?.total_amount || 0)"></p>
                <p x-show="lastOrder?.payment_type === 'CASH'" class="text-sm font-semibold mb-3" style="color: {{ $shop->getProperty('bg_color', '#A31F1F') }};" x-text="'Change: ' + formatRupiah(lastOrder?.change_amount || 0)"></p>
                <button @click="showSuccess = false; loadOrders()"
                        class="mt-4 w-full text-white font-semibold py-2.5 px-4 rounded-lg transition-all transform hover:scale-105 shadow-md hover:shadow-lg" style="background-color: {{ $shop->getProperty('bg_color', '#A31F1F') }}; color: {{ $shop->getProperty('text_color', '#F5E6D3') }};">
                    Continue
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Update shift timer
        const shiftStartTime = new Date('{{ $shift->login_time->toIso8601String() }}');
        
        function updateShiftTimer() {
            const now = new Date();
            const diff = Math.floor((now - shiftStartTime) / 1000);
            
            const hours = Math.floor(diff / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;
            
            const timerDisplay = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            const durationText = `${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m`;
            const currentTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            
            // Update all timer elements
            const durationHeaderEl = document.getElementById('shift-duration-header');
            if (durationHeaderEl) durationHeaderEl.textContent = timerDisplay;
            
            const durationMobileEl = document.getElementById('shift-duration-mobile');
            if (durationMobileEl) durationMobileEl.textContent = timerDisplay;
            
            const currentTimeEl = document.getElementById('shift-current-time');
            if (currentTimeEl) currentTimeEl.textContent = currentTime;
            
            const currentTimeMobileEl = document.getElementById('shift-current-time-mobile');
            if (currentTimeMobileEl) currentTimeMobileEl.textContent = currentTime;
        }
        
        updateShiftTimer();
        setInterval(updateShiftTimer, 1000);

        function posApp() {
            return {
                cart: {},
                cartTotal: 0,
                orders: [],
                showPayment: false,
                showSuccess: false,
                paymentType: '',
                cashReceived: '',
                processing: false,
                lastOrder: null,
                orderDetails: {},
                cartCollapsed: false,

                async loadCart() {
                    try {
                        const res = await fetch('{{ route("pos.cart.get") }}', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        this.cart = this.itemsToObject(data.items);
                        this.cartTotal = Number(data.total);
                    } catch (e) { console.error(e); }
                    this.loadOrders();
                    // Reset all modal states on page load
                    this.showSuccess = false;
                    this.showPayment = false;
                    this.processing = false;
                    // Ensure modals don't flash by waiting for Alpine initialization
                    setTimeout(() => {
                        this.showSuccess = false;
                        this.showPayment = false;
                    }, 300);
                },

                itemsToObject(items) {
                    const obj = {};
                    items.forEach(item => {
                        obj[item.id] = item;
                    });
                    return obj;
                },

                getTotalQuantity() {
                    return Object.values(this.cart).reduce((total, item) => total + item.quantity, 0);
                },

                async addToCart(productId) {
                    try {
                        const res = await fetch('{{ route("pos.cart.add") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ product_id: productId, quantity: 1 })
                        });
                        const data = await res.json();
                        this.cart = this.itemsToObject(data.items);
                        this.cartTotal = Number(data.total);
                    } catch (e) { console.error(e); }
                },

                async updateQty(cartId, qty) {
                    try {
                        const res = await fetch('{{ route("pos.cart.update") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ cart_id: cartId, quantity: qty })
                        });
                        const data = await res.json();
                        this.cart = this.itemsToObject(data.items);
                        this.cartTotal = Number(data.total);
                    } catch (e) { console.error(e); }
                },

                async removeItem(cartId) {
                    try {
                        const res = await fetch('{{ route("pos.cart.remove") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ cart_id: cartId })
                        });
                        const data = await res.json();
                        this.cart = this.itemsToObject(data.items);
                        this.cartTotal = Number(data.total);
                    } catch (e) { console.error(e); }
                },

                async clearCart() {
                    if (!confirm('Clear all items from cart?')) return;
                    try {
                        const res = await fetch('{{ route("pos.cart.clear") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const data = await res.json();
                        this.cart = this.itemsToObject(data.items);
                        this.cartTotal = Number(data.total);
                    } catch (e) { console.error(e); }
                },

                handleKeypad(key) {
                    if (key === '⌫') {
                        this.cashReceived = this.cashReceived.slice(0, -1);
                    } else {
                        this.cashReceived += key;
                    }
                },

                async submitOrder() {
                    this.processing = true;
                    try {
                        const body = { payment_type: this.paymentType };
                        if (this.paymentType === 'CASH') {
                            body.cash_received = Number(this.cashReceived);
                        }
                        const res = await fetch('{{ route("orders.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(body)
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.lastOrder = data.order;
                            this.cart = {};
                            this.cartTotal = 0;
                            this.showPayment = false;
                            this.showSuccess = true;
                            this.paymentType = '';
                            this.cashReceived = '';
                        } else {
                            alert(data.error || 'Failed to place order');
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Failed to place order');
                    }
                    this.processing = false;
                },

                async loadOrders() {
                    try {
                        const res = await fetch('{{ route("orders.history") }}', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        this.orders = data.orders;
                    } catch (e) { console.error(e); }
                },

                async loadOrderDetails(orderId) {
                    try {
                        const res = await fetch('/api/orders/' + orderId + '/details', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        this.orderDetails = data;
                    } catch (e) { console.error(e); }
                },

                formatRupiah(amount) {
                    return 'Rp ' + Number(amount).toLocaleString('id-ID');
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
