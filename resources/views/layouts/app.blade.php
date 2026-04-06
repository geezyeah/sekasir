<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Alpine.js for interactive components -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts -->
        
        @if($shopProperties ?? false)
            <style>
                body {
                    background-color: {{ $shopProperties['bg_color'] }};
                    color: {{ $shopProperties['text_color'] }};
                }
                
                .shop-themed {
                    background-color: {{ $shopProperties['bg_color'] }};
                    color: {{ $shopProperties['text_color'] }};
                }
                
                .shop-primary {
                    background-color: {{ $shopProperties['primary_color'] }};
                }
            </style>
        @endif
    </head>
    <body class="font-sans antialiased" style="@if($shopProperties ?? false) background-color: {{ $shopProperties['bg_color'] }}; color: {{ $shopProperties['text_color'] }}; @endif">
        <div class="min-h-screen" style="@if($shopProperties ?? false) background-color: {{ $shopProperties['bg_color'] }}; @else background-color: rgb(243, 244, 246); @endif">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="text-white">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
