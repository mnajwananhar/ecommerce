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
        <div class="max-w-[95vw] sm:max-w-[90vw] mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Footer Content -->
            <div class="grid gap-8 sm:grid-cols-3 lg:grid-cols-4">
                <!-- Kolom 1: About Platform -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">About Our Marketplace</h4>
                    <p class="text-gray-400 text-sm">
                        Welcome to our public e-commerce platform! A place where buyers and sellers come together to
                        trade high-quality products. Join us today and start shopping or selling!
                    </p>
                    <div class="mt-4 flex space-x-6">
                        <a href="#" class="hover:text-white transition-colors duration-300">
                            <img src="https://img.icons8.com/ios-filled/50/ffffff/facebook-new.png" alt="Facebook"
                                class="h-6 w-6">
                        </a>
                        <a href="#" class="hover:text-white transition-colors duration-300">
                            <img src="https://img.icons8.com/ios-filled/50/ffffff/twitter.png" alt="Twitter"
                                class="h-6 w-6">
                        </a>
                        <a href="#" class="hover:text-white transition-colors duration-300">
                            <img src="https://img.icons8.com/ios-filled/50/ffffff/instagram-new.png" alt="Instagram"
                                class="h-6 w-6">
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: For Buyers -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">For Buyers</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors duration-300">How to Shop</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Payment
                                Methods</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Shipping &
                                Delivery</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Return Policy</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">FAQs for
                                Buyers</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: For Sellers -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">For Sellers</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors duration-300">How to Sell</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Seller
                                Registration</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Selling Fees</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Seller
                                Dashboard</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">FAQs for
                                Sellers</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Legal & Support -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Legal & Support</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Terms of
                                Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Privacy Policy</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Security
                                Information</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Contact
                                Support</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Report a
                                Problem</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright Section -->
            <div class="mt-8 border-t border-gray-700 pt-6 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} Public E-Commerce Marketplace. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</body>

</html>
