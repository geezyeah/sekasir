<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('auth.full_name')" class="text-sm font-semibold text-gray-700" />
            <x-text-input 
                id="name" 
                class="block mt-2 w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                type="text" 
                name="name" 
                :value="old('name')" 
                placeholder="John Doe"
                required 
                autofocus 
                autocomplete="name" 
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('auth.email_address')" class="text-sm font-semibold text-gray-700" />
            <x-text-input 
                id="email" 
                class="block mt-2 w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                type="email" 
                name="email" 
                :value="old('email')" 
                placeholder="john@sekasir.com"
                required 
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

        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 pt-2">
            <a class="text-sm text-blue-600 hover:text-blue-700 hover:underline transition" href="{{ route('login') }}">
                {{ __('Already have an account?') }}
            </a>

            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('Create Account') }}
            </button>
        </div>
    </form>
</x-guest-layout>
