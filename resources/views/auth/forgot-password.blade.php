<x-guest-layout>
    <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-blue-50 border-l-4 border-blue-600 rounded">
        <p class="text-sm text-blue-800">
            Answer the security question to reset your password.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 sm:mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Security Question -->
        <div>
            <x-input-label for="security_answer" :value="__('What is Admin\'s favorite pet? Ask them.')" class="text-sm font-semibold text-gray-700" />
            <x-text-input 
                id="security_answer" 
                class="block mt-2 w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                type="text" 
                name="security_answer" 
                :value="old('security_answer')" 
                placeholder="Enter your answer"
                required 
                autofocus 
            />
            <x-input-error :messages="$errors->get('security_answer')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 pt-2">
            <a class="text-sm text-blue-600 hover:text-blue-700 hover:underline transition" href="{{ route('login') }}">
                {{ __('Back to login') }}
            </a>

            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('Verify Answer') }}
            </button>
        </div>
    </form>
</x-guest-layout>
