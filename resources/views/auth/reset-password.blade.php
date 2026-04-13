<x-guest-layout>
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-600 rounded">
        <p class="text-sm text-green-800">
            {{ __('auth.security_passed') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4 sm:space-y-5">
        @csrf

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
                autocomplete="new-password" 
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('auth.confirm_password_title')" class="text-sm font-semibold text-gray-700" />

            <x-text-input 
                id="password_confirmation" 
                class="block mt-2 w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                type="password"
                name="password_confirmation" 
                placeholder="••••••••"
                required 
                autocomplete="new-password" 
            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center pt-2">
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('Reset Password') }}
            </button>
        </div>

        <div class="text-center pt-2">
            <a class="text-sm text-blue-600 hover:text-blue-700 hover:underline transition" href="{{ route('login') }}">
                {{ __('Back to login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
