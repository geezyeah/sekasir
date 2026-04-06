<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Alpine.js for interactive components -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts -->
        <style>
            :root {
                --primary-blue: #242f6d;
                --secondary-blue: #3a4a8a;
                --light-blue: #4f5a9f;
            }
            
            @media (max-width: 424px) {
                .login-card {
                    max-width: 100% !important;
                    min-width: 0 !important;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased" style="background: linear-gradient(135deg, #242f6d 0%, #3a4a8a 100%);">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full -mt-48 -ml-48"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mb-48 -mr-48"></div>
            
            <div class="relative z-10">
                <!-- Logo/Brand Section -->
                <div class="mb-6 sm:mb-8 mt-4 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-white bg-opacity-10 rounded-full backdrop-blur-md border border-white border-opacity-20 mb-3 sm:mb-4">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-white">SEKASIR</h1>
                    <p class="text-white text-opacity-80 text-xs sm:text-sm mt-1">Point of Sale Management System</p>
                </div>

                <!-- Login Card -->
                <div class="login-card w-full px-6 py-8 sm:px-8 sm:py-10 bg-white shadow-2xl overflow-hidden sm:rounded-2xl backdrop-blur-sm mb-4" style="max-width: 800px; min-width: 380px;">
                    <div class="mb-6 sm:mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome Back</h2>
                        <p class="text-gray-600 text-sm mt-1">Sign in to your account to continue</p>
                    </div>
                    
                    {{ $slot }}

                    <!-- Footer -->
                    <div class="mt-6 sm:mt-8 pt-6 border-t border-gray-200">
                        <p class="text-center text-xs text-gray-500">
                            © 2026 <span class="font-semibold text-gray-700">SEKASIR</span>. All rights reserved.
                        </p>
                    </div>
                </div>

                <!-- Support text -->
                <!-- <div class="text-center mt-6 sm:mt-8 text-white text-opacity-70 text-xs">
                    <p>Having trouble? <a href="mailto:support@sekasir.com" class="underline text-white hover:text-opacity-100 transition">Contact Support</a></p>
                </div> -->
            </div>
        </div>
    </body>
</html>
