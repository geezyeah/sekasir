<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-3 sm:mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('auth.email_address')" class="text-sm font-semibold text-gray-700" />
            <x-text-input 
                id="email" 
                class="block mt-2 w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                type="email" 
                name="email" 
                :value="old('email')" 
                placeholder="admin@sekasir.com"
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('auth.password')" class="text-sm font-semibold text-gray-700" />

            <x-text-input 
                id="password" 
                class="block mt-2 w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                type="password"
                name="password"
                placeholder="••••••••"
                required 
                autocomplete="current-password" 
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input 
                id="remember_me" 
                type="checkbox" 
                class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer" 
                name="remember"
            >
            <label for="remember_me" class="ml-2 text-sm text-gray-600 cursor-pointer hover:text-gray-800">
                {{ __('Keep me signed in') }}
            </label>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 pt-2">
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-700 hover:underline transition focus:outline-none" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif

            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('Log In') }}
            </button>
        </div>
    </form>
</x-guest-layout>
