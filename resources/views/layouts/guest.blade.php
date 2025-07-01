<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Background with gradient and pattern -->
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 relative">
            <!-- Decorative background elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-green-400/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-400/5 rounded-full blur-3xl"></div>
            </div>

            <!-- Main content -->
            <div class="relative flex flex-col justify-center items-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
                <!-- Logo/Brand section -->
                <div class="mb-8 text-center">
                    <div class="mx-auto h-20 w-20 bg-gradient-to-r from-blue-600 to-green-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ config('app.name', 'KreditKu') }}</h1>
                    <p class="text-gray-600 mt-1">Solusi Peminjaman Terpercaya</p>
                </div>

                <!-- Form container -->
                <div class="w-full max-w-md">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-8 relative">
                        <!-- Subtle gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-green-500/5 rounded-2xl"></div>
                        
                        <!-- Content -->
                        <div class="relative">
                            {{ $slot }}
                        </div>
                    </div>
                </div>

                <!-- Trust indicators -->
                <div class="mt-8 flex items-center justify-center space-x-6 text-sm text-gray-500">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        SSL Secure
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-blue-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        Data Protected
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-purple-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Verified
                    </div>
                </div>

                <!-- Footer links -->
                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>
                        Dengan melanjutkan, Anda menyetujui 
                        <a href="#" class="text-blue-600 hover:text-blue-500 font-medium">Syarat & Ketentuan</a> 
                        dan 
                        <a href="#" class="text-blue-600 hover:text-blue-500 font-medium">Kebijakan Privasi</a> kami
                    </p>
                </div>
            </div>

            <!-- Floating elements for visual appeal -->
            <div class="absolute top-20 left-10 w-3 h-3 bg-blue-400/30 rounded-full animate-pulse"></div>
            <div class="absolute top-40 right-20 w-2 h-2 bg-green-400/40 rounded-full animate-pulse delay-1000"></div>
            <div class="absolute bottom-32 left-20 w-4 h-4 bg-purple-400/20 rounded-full animate-pulse delay-500"></div>
            <div class="absolute bottom-20 right-10 w-3 h-3 bg-blue-400/25 rounded-full animate-pulse delay-700"></div>
        </div>
    </body>
</html>