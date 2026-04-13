<x-guest-layout>
    <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-600 rounded">
        <p class="text-sm text-yellow-800">
            {{ __('auth.confirm_required') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
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
                autocomplete="current-password" 
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center pt-2">
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('common.save') }}
            </button>
        </div>
    </form>
</x-guest-layout>
