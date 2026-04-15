<x-app-layout>
    @php
        $user = Auth::user();
        $activeShift = $user->activeShift;
        $shop = $activeShift?->shop;
        
        // Use shop branding if available, otherwise use default admin colors
        $bgColor = $shop?->getProperty('bg_color', '#242f6d') ?? '#242f6d';
        $textColor = $shop?->getProperty('text_color', '#ffffff') ?? '#ffffff';
    @endphp

    <style>
        body {
            background-color: {{ $bgColor }};
        }
        header {
            background-color: {{ $bgColor }} !important;
        }
        nav {
            background-color: {{ $bgColor }} !important;
        }
        /* Ensure navbar background on mobile */
        header, nav, [role="navigation"] {
            background-color: {{ $bgColor }} !important;
        }
        /* Target responsive header elements */
        @media (max-width: 640px) {
            header, nav, header *, nav * {
                background-color: {{ $bgColor }} !important;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl leading-tight" style="color: {{ $textColor }};">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('dashboard'), 'label' => 'Dashboard'],
        ['url' => route('profile.edit'), 'label' => 'Profile']
    ]" />

    <div class="py-2 sm:py-12" style="background-color: {{ $bgColor }};">
        <div class="max-w-3xl mx-auto px-2 sm:px-6 lg:px-8 space-y-3 sm:space-y-6">
            <div class="p-3 sm:p-8 bg-white shadow sm:rounded-lg border-l-4" style="border-color: {{ $bgColor }};">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form', ['bgColor' => $bgColor])
                </div>
            </div>

            <div class="p-3 sm:p-8 bg-white shadow sm:rounded-lg border-l-4" style="border-color: {{ $bgColor }};">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form', ['bgColor' => $bgColor])
                </div>
            </div>

            <div class="p-3 sm:p-8 bg-white shadow sm:rounded-lg border-l-4" style="border-color: {{ $bgColor }};">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form', ['bgColor' => $bgColor])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
