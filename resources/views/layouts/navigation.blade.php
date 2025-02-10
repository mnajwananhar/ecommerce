@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
@endphp
<nav x-data="{ open: false, userDropdown: false }" class="bg-gray-100 border-b border-gray-300">
    <div class="max-w-[95vw] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo - Hidden on Mobile -->
            <div class="flex-shrink-0 flex items-center sm:flex">
                <a href="{{ route('welcome') }}" class="text-[#FF9C08] font-bold text-xl flex items-center">
                    <img src="{{ asset('./logo.png') }}" alt="Small View" class="w-6 my-4 sm:hidden">
                    <img src="{{ asset('./logo.png') }}" alt="Large View" class="w-10 hidden sm:block">
                </a>
            </div>

            <!-- Mobile Navigation -->
            <div class="flex items-center justify-between w-full sm:hidden space-x-2">
                <!-- Category Dropdown with Filter Icon for Mobile -->
                <x-category-dropdown-mobile /> <!-- Kita akan buat komponen baru ini -->

                <!-- Search Bar -->
                <div class="flex-1 max-w-5xl mx-2">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input type="text" name="query" value="{{ request('query') }}"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#FF9C08]"
                            placeholder="Search...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <button type="submit" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Mobile Cart and Orders Icons -->
                @auth
                    @if (Auth::user()->role !== 'admin')
                        <a href="{{ route('cart.index') }}" class="p-2 text-gray-600 hover:text-[#FF9C08]">
                            <div class="relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                @if ($cartItemCount > 0)
                                    <span
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs px-2 py-1">
                                        {{ $cartItemCount }}
                                    </span>
                                @endif
                            </div>
                        </a>
                        <a href="{{ route('orders.history') }}" class="p-2 text-gray-600 hover:text-[#FF9C08]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>
                    @endif
                @endauth

                <!-- Mobile User Menu -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-600 hover:text-[#FF9C08]">
                            @if (Auth::user()->profile_photo)
                                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile Photo"
                                    class="w-8 h-8 rounded-full">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            @endif
                        </button>

                        <!-- Mobile Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-50 mt-2 w-48 bg-white rounded-lg shadow-lg py-1">

                            @if (Auth::user()->role === 'seller')
                                @if (auth()->user()->shop)
                                    <div x-data="{ manageOpen: false }" class="relative">
                                        <button @click="manageOpen = !manageOpen"
                                            class="flex items-center justify-between w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>Manage</span>
                                            </div>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="manageOpen" class="bg-white rounded-lg shadow-lg mt-1">
                                            <a href="{{ route('seller.dashboard') }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                </svg>
                                                <span>{{ __('Dashboard') }}</span>
                                            </a>
                                            <a href="{{ route('shops.show', ['shop' => auth()->user()->shop?->shop_name]) }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                                                </svg>
                                                <span>{{ __('My Shop') }}</span>
                                            </a>
                                            <a href="{{ route('shops.edit', ['shop' => auth()->user()->shop?->shop_name]) }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                                <span>Shop</span>
                                            </a>
                                            <a href="{{ route('products.manage.index') }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                <span>{{ __('Products') }}</span>
                                            </a>
                                            <a href="{{ route('orders.manage') }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                </svg>
                                                <span>{{ __('Orders') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('shops.create') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                        {{ __('Create Shop') }}
                                    </a>
                                @endif
                            @endif

                            @if (Auth::user()->role === 'customer')
                                <a href="{{ route('seller-request.create') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                    {{ __('Become a Seller') }}
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                {{ __('Profile') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-[#FFF5E6]">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
                @guest
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 text-gray-600 hover:text-[#FF9C08] flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Mobile Dropdown Menu -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-50 mt-2 w-48 bg-white rounded-lg shadow-lg py-1">

                            <a href="{{ route('login') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                {{ __('Login') }}
                            </a>
                            <a href="{{ route('register') }}"
                                class="block px-4 py-2 text-sm font-medium text-[#FF9C08] hover:bg-[#FFF5E6]">
                                {{ __('Sign Up') }}
                            </a>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center sm:space-x-4 sm:mx-4">
                <!-- Category Dropdown for Desktop -->
                <x-category-dropdown />

                <!-- Search Bar -->
                <div class="flex-1 max-w-5xl">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input type="text" name="query" value="{{ request('query') }}"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#FF9C08]"
                            placeholder="Search...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <button type="submit" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />s
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Cart and Orders Links (Only show if not admin) -->
                @auth
                    @if (Auth::user()->role !== 'admin')
                        <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')" class="text-gray-800 hover:text-[#FF9C08]">
                            <div class="relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                @if ($cartItemCount > 0)
                                    <spans
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs px-2 py-1">
                                        {{ $cartItemCount }}
                                    </spans>
                                @endif
                            </div>
                        </x-nav-link>
                        <x-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')"
                            class="text-gray-800 hover:text-[#FF9C08] flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </x-nav-link>
                    @endif
                @endauth
            </div>

            <!-- Desktop Right Side -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Mobile Menu Button -->
                <div class="sm:hidden">
                    <button @click="open = !open" class="text-gray-800 hover:text-[#FF9C08]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
                @guest
                    <a href="{{ route('login') }}" class="text-gray-800 hover:text-[#FF9C08] font-medium">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-[#FF9C08] text-white px-4 py-2 rounded-lg hover:bg-[#E68A00]">Sign Up</a>
                @endguest
                @auth
                    @if (Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-gray-800 hover:text-[#FF9C08]">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('seller-requests.index')" :active="request()->routeIs('seller-requests.index')" class="text-gray-800 hover:text-[#FF9C08]">
                            {{ __('Seller Requests') }}
                        </x-nav-link>
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')" class="text-gray-800 hover:text-[#FF9C08]">
                            {{ __('Categories') }}
                        </x-nav-link>
                        <!-- Added logout button for admin -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium ml-4">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    @else
                        <!-- Vertical Divider -->
                        <div class="h-6 w-px bg-gray-300"></div>

                        <!-- Shop Links based on role -->
                        @if (Auth::user()->role === 'seller')
                            @if (auth()->user()->shop)
                                <x-nav-link :href="route('shops.show', ['shop' => auth()->user()->shop?->shop_name])" :active="request()->routeIs('shops.show')"
                                    class="text-gray-800 hover:text-[#FF9C08] flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                                    </svg>
                                    <span class="text-sm">{{ __('My Shop') }}</span>
                                </x-nav-link>
                            @else
                                <x-nav-link :href="route('shops.create')" :active="request()->routeIs('shops.create')"
                                    class="text-gray-800 hover:text-[#FF9C08] flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                                    </svg>
                                    <span class="text-sm">{{ __('Create Shop') }}</span>
                                </x-nav-link>
                            @endif
                        @endif

                        <!-- User Dropdown -->
                        <div class="hidden sm:block relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 text-gray-600 hover:text-[#FF9C08]">
                                @if (Auth::user()->profile_photo)
                                    <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile Photo"
                                        class="w-8 h-8 rounded-full">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                @endif
                                <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Desktop Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-50 mt-2 w-56 bg-white rounded-lg shadow-lg divide-y divide-gray-100">

                                @if (Auth::user()->role === 'seller')
                                    <div x-data="{ manageOpen: false }" class="relative">
                                        <!-- Manage Menu Trigger -->
                                        <button @click="manageOpen = !manageOpen"
                                            class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6] rounded-t-lg">
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>Manage</span>
                                            </div>
                                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': manageOpen }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <!-- Manage Submenu -->
                                        <div x-show="manageOpen" x-transition class="relative bg-gray-50 rounded-b-lg">
                                            <a href="{{ route('seller.dashboard') }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-[#FFF5E6]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                </svg>
                                                <span>Dashboard</span>
                                            </a>
                                            @if (auth()->user()->shop)
                                                <a href="{{ route('shops.edit', ['shop' => auth()->user()->shop?->shop_name]) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-[#FFF5E6]">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                                                    </svg>
                                                    <span>Shop</span>
                                                </a>
                                            @endif
                                            <a href="{{ route('products.manage.index') }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-[#FFF5E6]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                <span>Product</span>
                                            </a>
                                            <a href="{{ route('orders.manage') }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-[#FFF5E6]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                </svg>
                                                <span>Orders</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if (Auth::user()->role === 'customer')
                                    <a href="{{ route('seller-request.create') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">
                                        {{ __('Apply to be a Seller') }}
                                    </a>
                                @endif
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6]">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-[#FFF5E6]">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div x-show="open" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if (Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('seller-requests.index')" :active="request()->routeIs('seller-requests.index')">
                        {{ __('Seller Requests') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')">
                        {{ __('Categories') }}
                    </x-responsive-nav-link>
                @else
                    @if (Auth::user()->role === 'seller')
                        @if (auth()->user()->shop)
                            <x-responsive-nav-link :href="route('shops.show', ['shop' => auth()->user()->shop?->shop_name])" :active="request()->routeIs('shops.show')">
                                {{ __('My Shop') }}
                            </x-responsive-nav-link>
                        @else
                            <x-responsive-nav-link :href="route('shops.create')" :active="request()->routeIs('shops.create')">
                                {{ __('Create Shop') }}
                            </x-responsive-nav-link>
                        @endif
                    @endif
                    <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                        {{ __('Cart') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">
                        {{ __('Orders') }}
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Logout') }}
                    </x-responsive-nav-link>
                </form>
            @endauth
            @guest
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    {{ __('Login') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                    {{ __('Sign Up') }}
                </x-responsive-nav-link>
            @endguest
        </div>
    </div>
</nav>
