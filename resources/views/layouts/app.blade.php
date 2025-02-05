<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title> <!-- Title berubah per view -->
    <link rel="shortcut icon" href="{{ asset('logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Tambahkan di bagian head atau sebelum closing body -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-[95vw] sm:max-w-[90vw] mx-auto px-2 sm:px-4 lg:px-6">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="max-w-[95vw] sm:max-w-[90vw] mx-auto px-2 sm:px-4 lg:px-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-[95vw] sm:max-w-[90vw] mx-auto py-6 sm:py-8 px-2 sm:px-4 lg:px-6">
            <div class="grid gap-8 sm:grid-cols-3">
                <div>


                </div>
                <div class="sm:text-center">
                    <h4 class="text-lg font-semibold">Follow Us</h4>
                    <div class="mt-2 flex sm:justify-center space-x-4">
                        <a href="#" class="hover:underline">
                            <img src="https://img.icons8.com/ios-filled/50/ffffff/facebook-new.png" alt="Facebook"
                                class="h-6 w-6">
                        </a>
                        <a href="#" class="hover:underline">
                            <img src="https://img.icons8.com/ios-filled/50/ffffff/twitter.png" alt="Twitter"
                                class="h-6 w-6">
                        </a>
                        <a href="#" class="hover:underline">
                            <img src="https://img.icons8.com/ios-filled/50/ffffff/instagram-new.png" alt="Instagram"
                                class="h-6 w-6">
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-4 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} E-Commerce All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</body>

</html>
