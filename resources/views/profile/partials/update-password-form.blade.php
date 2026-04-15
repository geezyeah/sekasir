<section>
    <header class="mb-4 sm:mb-6 p-4 sm:p-5" style="background-color: {{ $bgColor ?? '#1f2937' }}; border-bottom: 3px solid rgba(255, 255, 255, 0.1);">
        <h2 class="text-base sm:text-lg font-bold" style="color: white;">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-xs sm:text-sm" style="color: rgba(255, 255, 255, 0.8);">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <div class="px-4 sm:px-5 py-4 sm:py-6">
        <form method="post" action="{{ route('password.update') }}" class="space-y-3 sm:space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 sm:mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 sm:mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 sm:mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90" style="background-color: {{ $bgColor ?? '#1f2937' }};">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs sm:text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
    </div>
</section>
