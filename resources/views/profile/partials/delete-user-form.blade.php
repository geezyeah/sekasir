<section class="space-y-3 sm:space-y-6 bg-white rounded-lg shadow">
    <header class="p-4 sm:p-5" style="background-color: #dc2626; border-bottom: 3px solid rgba(255, 255, 255, 0.1);">
        <h2 class="text-base sm:text-lg font-bold" style="color: white;">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-xs sm:text-sm" style="color: rgba(255, 255, 255, 0.8);">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="px-4 sm:px-5 py-4 sm:py-6">
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4 sm:p-6">
            @csrf
            @method('delete')

            <h2 class="text-base sm:text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-3 sm:mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1 sm:mt-2" />
            </div>

            <div class="mt-3 sm:mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
    </div>
</section>
