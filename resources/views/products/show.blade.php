@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>
    <div class="py-4 sm:py-12">
        <div class="max-w-[95vw] sm:max-w-[90vw] mx-auto px-2 sm:px-4 lg:px-6 space-y-6 sm:space-y-8">
            <!-- Product Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                <!-- Left Column: Image Gallery -->
                <div class="space-y-4">
                    <!-- Main Image Container -->
                    <div class="relative h-[300px] sm:h-[500px]">
                        <div id="carousel-images" class="h-full">
                            @foreach ($product->images as $key => $image)
                                @php
                                    $imagePath = Storage::url($image->image_path);
                                @endphp
                                <!-- Debug info -->
                                @if (config('app.debug'))
                                    <!-- {{ $imagePath }} -->
                                @endif
                                <img src="{{ $imagePath }}" alt="{{ $product->name }}"
                                    class="absolute inset-0 w-full h-full object-contain 
                            {{ $key === 0 ? 'block' : 'hidden' }} product-image">
                            @endforeach
                        </div>

                        <!-- Navigation Arrows -->
                        @if (count($product->images) > 1 && auth()->check())
                            <div class="absolute inset-y-0 left-0 right-0 flex items-center justify-between px-4">
                                <button onclick="prevImage()"
                                    class="bg-white/80 rounded-full p-2 shadow-xl hover:bg-[#FF9C08] hover:text-white transition-all duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button onclick="nextImage()"
                                    class="bg-white/80 rounded-full p-2 shadow-xl hover:bg-[#FF9C08] hover:text-white transition-all duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if (count($product->images) > 1 && auth()->check())
                        <div class="flex gap-2 overflow-x-auto py-2 scrollbar-hide">
                            @foreach ($product->images as $key => $image)
                                <button onclick="showImage({{ $key }})"
                                    class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden focus:outline-none focus:ring-2 focus:ring-[#FF9C08]
                        {{ $key === 0 ? 'ring-2 ring-[#FF9C08]' : '' }}">
                                    <img src="{{ Storage::url($image->image_path) }}"
                                        alt="Thumbnail {{ $key + 1 }}"
                                        class="w-full h-full object-cover rounded-lg">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right Column: Product Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:shadow-xl sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <!-- Product Title & Category -->
                        <div class="mb-6">
                            <h1 class="text-2xl sm:text-3xl font-bold text-[#FF9C08] mb-2">
                                {{ $product->name }}
                            </h1>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        </div>

                        <!-- Price, Weight & Stock Info -->
                        <div class="border-t border-b border-gray-200 py-4 mb-6">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Price -->
                                <div>
                                    <span class="text-sm text-gray-500 block mb-1">Price</span>
                                    <span class="text-2xl font-bold text-gray-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <!-- Weight -->
                                <div class="text-right">
                                    <span class="text-sm text-gray-500 block mb-1">Product Weight</span>
                                    <span class="text-lg font-medium text-gray-900">{{ $product->weight }}g</span>
                                </div>
                            </div>
                            <!-- Stock -->
                            <div class="mt-4">
                                <span class="text-sm text-gray-500 block mb-1">Availability</span>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-2 py-1 {{ $product->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} text-sm font-medium rounded">
                                        Stock: {{ $product->stock }}
                                    </span>
                                    @if ($product->stock < 10)
                                        <span class="text-sm text-red-600">Low stock!</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Product Description</h3>
                            <div class="prose prose-sm text-gray-600">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>

                        <!-- Shop Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Store Information</h3>
                            <div class="flex items-center gap-3">
                                @if ($product->shop->shop_logo)
                                    <img src="{{ Storage::url($product->shop->shop_logo) }}"
                                        alt="{{ $product->shop->shop_name }}"
                                        class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <span class="text-xl font-medium text-gray-500">
                                            {{ strtoupper(substr($product->shop->shop_name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $product->shop->shop_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $product->shop->shop_address_label }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Add to Cart Form -->
                        @if (auth()->check() && auth()->user()->role !== 'admin' && auth()->id() !== $product->shop->seller_id)
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <!-- Quantity Selector -->
                                    <div class="flex items-center gap-4">
                                        <label for="quantity" class="text-sm font-medium text-gray-700 min-w-[60px]">
                                            Quantity
                                        </label>
                                        <div class="inline-flex items-center rounded-lg border border-gray-300">
                                            <button type="button" onclick="decrementQuantity()"
                                                class="px-3 py-2 text-[#FF9C08] hover:text-white hover:bg-[#FF9C08] rounded-l-lg transition-colors duration-200">
                                                －
                                            </button>
                                            <input type="number" name="quantity" id="quantity" value="1"
                                                min="1"
                                                class="w-14 py-2 text-center border-x border-gray-300 focus:outline-none focus:ring-0"
                                                readonly data-max="{{ $product->stock }}">
                                            <button type="button" onclick="incrementQuantity()"
                                                class="px-3 py-2 text-[#FF9C08] hover:text-white hover:bg-[#FF9C08] rounded-r-lg transition-colors duration-200">
                                                ＋
                                            </button>
                                        </div>
                                        <span class="text-sm text-gray-500">
                                            Maximum {{ $product->stock }} units
                                        </span>
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 bg-[#FF9C08] text-white px-6 py-3 rounded-lg hover:bg-[#E68A00] focus:outline-none focus:ring-2 focus:ring-[#FF9C08] focus:ring-offset-2 transition-all shadow-lg hover:shadow-[#FF9C08]/20">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>Add to Cart</span>
                                    </button>
                                </div>
                            </form>
                        @endif

                        <!-- Error Message -->
                        @if (session('error'))
                            <div class="mt-4 rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-red-800">
                                            {{ session('error') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Product Details -->

            <!-- Reviews Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:shadow-xl sm:rounded-lg">
                <div class="p-3 sm:p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Product Reviews</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $product->reviews->count() }} reviews
                                @if ($product->reviews->avg('rating'))
                                    · {{ number_format($product->reviews->avg('rating'), 1) }} / 5
                                @endif
                            </p>
                        </div>
                        <!-- Rating Summary (Hidden on Mobile) -->
                        @if (!$product->reviews->isEmpty())
                            <div class="hidden sm:flex items-center gap-1">
                                <div class="flex text-yellow-400">
                                    @php
                                        $avgRating = $product->reviews->avg('rating');
                                        $roundedRating = round($avgRating * 2) / 2;
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $roundedRating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($product->reviews->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📝</div>
                            <p class="text-gray-500 mb-2">No reviews for this product yet.</p>
                            <p class="text-sm text-gray-400">Be the first to leave a review!</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach ($product->reviews->take(5) as $review)
                                <div class="py-6 first:pt-0">
                                    <div class="flex items-start gap-4">
                                        <!-- User Avatar -->
                                        <div class="flex-shrink-0">
                                            @if ($review->user->profile_photo)
                                                <img src="{{ Storage::url($review->user->profile_photo) }}"
                                                    alt="{{ $review->user->name }}"
                                                    class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="relative w-10 h-10">
                                                    <div
                                                        class="w-full h-full rounded-full bg-[#FF9C08]/10 flex items-center justify-center">
                                                        <span class="text-[#FF9C08] font-medium">
                                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    @if ($review->user->hasVerifiedEmail())
                                                        <div class="absolute -bottom-1 -right-1">
                                                            <svg class="w-4 h-4 text-blue-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <!-- User Info -->
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-medium text-gray-900">{{ $review->user->name }}
                                                        </p>
                                                        @if ($review->user->hasVerifiedEmail())
                                                            <span class="inline-block sm:hidden text-blue-500">
                                                                <svg class="w-4 h-4" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <!-- Additional User Info -->
                                                    <div class="text-xs text-gray-500 mt-0.5">
                                                        @if ($review->user->location)
                                                            <span>📍 {{ $review->user->location }}</span>
                                                        @endif
                                                        <span>· {{ $review->user->reviews->count() }} reviews</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 mt-2">
                                                        <div class="flex text-yellow-400">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                        <span class="text-xs text-gray-500">
                                                            {{ $review->created_at->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <p class="text-gray-700 text-sm">{{ $review->review }}</p>
                                                @if ($review->image)
                                                    <div class="mt-3">
                                                        <img src="{{ Storage::url($review->image) }}"
                                                            alt="Review Image"
                                                            class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                                            onclick="window.open(this.src, '_blank')">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($product->reviews->count() > 5)
                            <div class="mt-6 text-center">
                                <button type="button" onclick="showAllReviews()"
                                    class="text-[#FF9C08] hover:text-[#E68A00] text-sm font-medium">
                                    View All Reviews ({{ $product->reviews->count() }})
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <!-- End Reviews Section -->

            <!-- Other Products Section -->
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">
                    Other Products from {{ $product->shop->shop_name }}
                </h2>
                @php
                    $otherProducts = $product->shop
                        ->products()
                        ->where('id', '!=', $product->id)
                        ->whereNull('deleted_at')
                        ->take(6)
                        ->get();
                @endphp
                @if ($otherProducts->isEmpty())
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🏪</div>
                        <p class="text-gray-500 mb-2">No other products from this store.</p>
                        <p class="text-sm text-gray-400">Try again later!</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach ($otherProducts as $otherProduct)
                            <a href="{{ route('products.show', $otherProduct->id) }}" class="block">
                                <div
                                    class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group h-full flex flex-col">
                                    <!-- Image Container -->
                                    <div class="relative w-full h-48 flex-shrink-0">
                                        @if ($otherProduct->images->isNotEmpty())
                                            <img src="{{ Storage::url($otherProduct->images->first()->image_path) }}"
                                                alt="{{ $otherProduct->name }}"
                                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div
                                                class="absolute inset-0 w-full h-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Product Info -->
                                    <div class="p-3 flex flex-col flex-grow">
                                        <!-- Shop Info -->
                                        <div class="flex items-center gap-1 mb-1.5">
                                            @if ($otherProduct->shop->shop_logo)
                                                <img src="{{ Storage::url($otherProduct->shop->shop_logo) }}"
                                                    alt="{{ $otherProduct->shop->shop_name }}"
                                                    class="w-4 h-4 rounded-full">
                                            @endif
                                            <span class="text-xs text-gray-600 truncate">
                                                {{ $otherProduct->shop->shop_name }}
                                            </span>
                                        </div>
                                        <!-- Product Name -->
                                        <h3 class="text-xs font-medium text-gray-800 line-clamp-2 min-h-[32px] mb-1.5">
                                            {{ $otherProduct->name }}
                                        </h3>
                                        <!-- Badges -->
                                        <div class="flex flex-wrap gap-1 mb-1.5">
                                            <span
                                                class="px-1.5 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-medium rounded">
                                                {{ $otherProduct->category->name }}
                                            </span>
                                            @if ($otherProduct->stock > 0)
                                                <span
                                                    class="px-1.5 py-0.5 {{ $otherProduct->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} text-[10px] font-medium rounded">
                                                    {{ $otherProduct->stock }}
                                                </span>
                                            @endif
                                        </div>
                                        <!-- Price & Rating -->
                                        <div class="mt-auto">
                                            <div class="flex items-center justify-between mb-1.5">
                                                <p class="text-sm font-bold text-gray-800">
                                                    Rp {{ number_format($otherProduct->price, 0, ',', '.') }}
                                                </p>
                                                @if ($otherProduct->discount_price)
                                                    <p class="text-xs text-gray-500 line-through">
                                                        Rp
                                                        {{ number_format($otherProduct->original_price, 0, ',', '.') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <div class="flex items-center">
                                                    @php
                                                        $rating = $otherProduct->reviews()->avg('rating') ?? 0;
                                                        $roundedRating = round($rating * 2) / 2;
                                                    @endphp
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="w-3.5 h-3.5 {{ $i <= $roundedRating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-xs text-gray-600">
                                                    ({{ $otherProduct->reviews->count() }})
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            <!-- End Other Products Section -->

            <!-- Button to Navigate to Seller's Shop -->
            <div class="text-center mt-8">
                <a href="{{ route('shops.show', $product->shop->shop_name) }}"
                    class="inline-block bg-[#FF9C08] text-white px-6 py-3 rounded-lg hover:bg-[#E68A00] focus:outline-none focus:ring-2 focus:ring-[#FF9C08] focus:ring-offset-2 transition-all shadow-lg hover:shadow-[#FF9C08]/20">
                    View Seller's Shop
                </a>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .navigation-button {
            @apply bg-white/80 rounded-full p-2 hover:bg-[#FF9C08] hover:text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#FF9C08];
        }

        .quantity-button {
            @apply px-3 py-1 bg-gray-50 hover:bg-[#FF9C08] hover:text-white transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-[#FF9C08];
        }
    </style>

    <!-- Scripts -->
    <script>
        let currentImageIndex = 0;
        let images;
        let thumbnails;

        document.addEventListener('DOMContentLoaded', () => {
            images = document.querySelectorAll('.product-image');
            thumbnails = document.querySelectorAll('.thumbnail-btn');
            showImage(0);
        });

        function showImage(index) {
            images.forEach(img => img.classList.add('hidden'));
            thumbnails.forEach(thumb => thumb.classList.remove('ring-2', 'ring-[#FF9C08]'));

            currentImageIndex = index;
            images[index].classList.remove('hidden');
            images[index].classList.add('block');
            thumbnails[index].classList.add('ring-2', 'ring-[#FF9C08]');
        }

        function prevImage() {
            const newIndex = (currentImageIndex - 1 + images.length) % images.length;
            showImage(newIndex);
        }

        function nextImage() {
            const newIndex = (currentImageIndex + 1) % images.length;
            showImage(newIndex);
        }

        function incrementQuantity() {
            const input = document.getElementById('quantity');
            const maxQuantity = parseInt(input.getAttribute('data-max'));
            if (parseInt(input.value) < maxQuantity) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decrementQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function showAllReviews() {
            document.getElementById('reviewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('reviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });
    </script>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="min-h-screen px-4 text-center">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    All Reviews
                                </h3>
                                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-500">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2 max-h-[calc(100vh-200px)] overflow-y-auto">
                                <div class="divide-y divide-gray-200">
                                    @foreach ($product->reviews as $review)
                                        <div class="py-6 first:pt-0">
                                            <div class="flex items-start gap-4">
                                                <!-- User Avatar -->
                                                <div class="flex-shrink-0">
                                                    @if ($review->user->profile_photo)
                                                        <img src="{{ Storage::url($review->user->profile_photo) }}"
                                                            alt="{{ $review->user->name }}"
                                                            class="w-10 h-10 rounded-full object-cover">
                                                    @else
                                                        <div class="relative w-10 h-10">
                                                            <div
                                                                class="w-full h-full rounded-full bg-[#FF9C08]/10 flex items-center justify-center">
                                                                <span class="text-[#FF9C08] font-medium">
                                                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                            @if ($review->user->hasVerifiedEmail())
                                                                <div class="absolute -bottom-1 -right-1">
                                                                    <svg class="w-4 h-4 text-blue-500"
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <!-- User Info -->
                                                            <div class="flex items-center gap-2">
                                                                <p class="font-medium text-gray-900">
                                                                    {{ $review->user->name }}</p>
                                                                @if ($review->user->hasVerifiedEmail())
                                                                    <span class="inline-block sm:hidden text-blue-500">
                                                                        <svg class="w-4 h-4" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!-- Additional User Info -->
                                                            <div class="text-xs text-gray-500 mt-0.5">
                                                                @if ($review->user->location)
                                                                    <span>📍 {{ $review->user->location }}</span>
                                                                @endif
                                                                <span>· {{ $review->user->reviews->count() }}
                                                                    reviews</span>
                                                            </div>
                                                            <div class="flex items-center gap-2 mt-2">
                                                                <div class="flex text-yellow-400">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                            fill="currentColor" viewBox="0 0 20 20">
                                                                            <path
                                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                        </svg>
                                                                    @endfor
                                                                </div>
                                                                <span class="text-xs text-gray-500">
                                                                    {{ $review->created_at->format('d M Y') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <p class="text-gray-700 text-sm">{{ $review->review }}</p>
                                                        @if ($review->image)
                                                            <div class="mt-3">
                                                                <img src="{{ Storage::url($review->image) }}"
                                                                    alt="Review Image"
                                                                    class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                                                    onclick="window.open(this.src, '_blank')">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Reviews Section -->

            <!-- Other Products Section -->
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">
                    Other Products from {{ $product->shop->shop_name }}
                </h2>
                @php
                    $otherProducts = $product->shop
                        ->products()
                        ->where('id', '!=', $product->id)
                        ->whereNull('deleted_at')
                        ->take(6)
                        ->get();
                @endphp
                @if ($otherProducts->isEmpty())
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🏪</div>
                        <p class="text-gray-500 mb-2">No other products from this store.</p>
                        <p class="text-sm text-gray-400">Try again later!</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach ($otherProducts as $otherProduct)
                            <a href="{{ route('products.show', $otherProduct->id) }}" class="block">
                                <div
                                    class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group h-full flex flex-col">
                                    <!-- Image Container -->
                                    <div class="relative w-full h-48 flex-shrink-0">
                                        @if ($otherProduct->images->isNotEmpty())
                                            <img src="{{ Storage::url($otherProduct->images->first()->image_path) }}"
                                                alt="{{ $otherProduct->name }}"
                                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div
                                                class="absolute inset-0 w-full h-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Product Info -->
                                    <div class="p-3 flex flex-col flex-grow">
                                        <!-- Shop Info -->
                                        <div class="flex items-center gap-1 mb-1.5">
                                            @if ($otherProduct->shop->shop_logo)
                                                <img src="{{ Storage::url($otherProduct->shop->shop_logo) }}"
                                                    alt="{{ $otherProduct->shop->shop_name }}"
                                                    class="w-4 h-4 rounded-full">
                                            @endif
                                            <span class="text-xs text-gray-600 truncate">
                                                {{ $otherProduct->shop->shop_name }}
                                            </span>
                                        </div>
                                        <!-- Product Name -->
                                        <h3 class="text-xs font-medium text-gray-800 line-clamp-2 min-h-[32px] mb-1.5">
                                            {{ $otherProduct->name }}
                                        </h3>
                                        <!-- Badges -->
                                        <div class="flex flex-wrap gap-1 mb-1.5">
                                            <span
                                                class="px-1.5 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-medium rounded">
                                                {{ $otherProduct->category->name }}
                                            </span>
                                            @if ($otherProduct->stock > 0)
                                                <span
                                                    class="px-1.5 py-0.5 {{ $otherProduct->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} text-[10px] font-medium rounded">
                                                    {{ $otherProduct->stock }}
                                                </span>
                                            @endif
                                        </div>
                                        <!-- Price & Rating -->
                                        <div class="mt-auto">
                                            <div class="flex items-center justify-between mb-1.5">
                                                <p class="text-sm font-bold text-gray-800">
                                                    Rp {{ number_format($otherProduct->price, 0, ',', '.') }}
                                                </p>
                                                @if ($otherProduct->discount_price)
                                                    <p class="text-xs text-gray-500 line-through">
                                                        Rp
                                                        {{ number_format($otherProduct->original_price, 0, ',', '.') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <div class="flex items-center">
                                                    @php
                                                        $rating = $otherProduct->reviews()->avg('rating') ?? 0;
                                                        $roundedRating = round($rating * 2) / 2;
                                                    @endphp
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="w-3.5 h-3.5 {{ $i <= $roundedRating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-xs text-gray-600">
                                                    ({{ $otherProduct->reviews->count() }})
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            <!-- End Other Products Section -->

            <!-- Button to Navigate to Seller's Shop -->
            <div class="text-center mt-8">
                <a href="{{ route('shops.show', $product->shop->shop_name) }}"
                    class="inline-block bg-[#FF9C08] text-white px-6 py-3 rounded-lg hover:bg-[#E68A00] focus:outline-none focus:ring-2 focus:ring-[#FF9C08] focus:ring-offset-2 transition-all shadow-lg hover:shadow-[#FF9C08]/20">
                    View Seller's Shop
                </a>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .navigation-button {
            @apply bg-white/80 rounded-full p-2 hover:bg-[#FF9C08] hover:text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#FF9C08];
        }

        .quantity-button {
            @apply px-3 py-1 bg-gray-50 hover:bg-[#FF9C08] hover:text-white transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-[#FF9C08];
        }
    </style>

    <!-- Scripts -->
    <script>
        let currentImageIndex = 0;
        let images;
        let thumbnails;

        document.addEventListener('DOMContentLoaded', () => {
            images = document.querySelectorAll('.product-image');
            thumbnails = document.querySelectorAll('.thumbnail-btn');
            showImage(0);
        });

        function showImage(index) {
            images.forEach(img => img.classList.add('hidden'));
            thumbnails.forEach(thumb => thumb.classList.remove('ring-2', 'ring-[#FF9C08]'));

            currentImageIndex = index;
            images[index].classList.remove('hidden');
            images[index].classList.add('block');
            thumbnails[index].classList.add('ring-2', 'ring-[#FF9C08]');
        }

        function prevImage() {
            const newIndex = (currentImageIndex - 1 + images.length) % images.length;
            showImage(newIndex);
        }

        function nextImage() {
            const newIndex = (currentImageIndex + 1) % images.length;
            showImage(newIndex);
        }

        function incrementQuantity() {
            const input = document.getElementById('quantity');
            const maxQuantity = parseInt(input.getAttribute('data-max'));
            if (parseInt(input.value) < maxQuantity) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decrementQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function showAllReviews() {
            document.getElementById('reviewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('reviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });
    </script>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="min-h-screen px-4 text-center">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    All Reviews
                                </h3>
                                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-500">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2 max-h-[calc(100vh-200px)] overflow-y-auto">
                                <div class="divide-y divide-gray-200">
                                    @foreach ($product->reviews as $review)
                                        <div class="py-6 first:pt-0">
                                            <div class="flex items-start gap-4">
                                                <!-- User Avatar -->
                                                <div class="flex-shrink-0">
                                                    @if ($review->user->profile_photo)
                                                        <img src="{{ Storage::url($review->user->profile_photo) }}"
                                                            alt="{{ $review->user->name }}"
                                                            class="w-10 h-10 rounded-full object-cover">
                                                    @else
                                                        <div class="relative w-10 h-10">
                                                            <div
                                                                class="w-full h-full rounded-full bg-[#FF9C08]/10 flex items-center justify-center">
                                                                <span class="text-[#FF9C08] font-medium">
                                                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                            @if ($review->user->hasVerifiedEmail())
                                                                <div class="absolute -bottom-1 -right-1">
                                                                    <svg class="w-4 h-4 text-blue-500"
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <!-- User Info -->
                                                            <div class="flex items-center gap-2">
                                                                <p class="font-medium text-gray-900">
                                                                    {{ $review->user->name }}</p>
                                                                @if ($review->user->hasVerifiedEmail())
                                                                    <span class="inline-block sm:hidden text-blue-500">
                                                                        <svg class="w-4 h-4" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!-- Additional User Info -->
                                                            <div class="text-xs text-gray-500 mt-0.5">
                                                                @if ($review->user->location)
                                                                    <span>📍 {{ $review->user->location }}</span>
                                                                @endif
                                                                <span>· {{ $review->user->reviews->count() }}
                                                                    reviews</span>
                                                            </div>
                                                            <div class="flex items-center gap-2 mt-2">
                                                                <div class="flex text-yellow-400">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                            fill="currentColor" viewBox="0 0 20 20">
                                                                            <path
                                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                        </svg>
                                                                    @endfor
                                                                </div>
                                                                <span class="text-xs text-gray-500">
                                                                    {{ $review->created_at->format('d M Y') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <p class="text-gray-700 text-sm">{{ $review->review }}</p>
                                                        @if ($review->image)
                                                            <div class="mt-3">
                                                                <img src="{{ Storage::url($review->image) }}"
                                                                    alt="Review Image"
                                                                    class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                                                    onclick="window.open(this.src, '_blank')">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Review Modal -->
        </div>
    </div>
</x-app-layout>
