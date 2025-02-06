@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>
    @section('title', 'Products seller') <!-- Title halaman Produk -->

    <!-- Products Grid -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="relative bg-gradient-to-r from-[#FF9C08] to-[#FFB347] bg-opacity-30 backdrop-blur-md rounded-lg py-8 md:py-12 shadow-md overflow-hidden mb-6 p-4 sm:p-6 text-white">
                <div class="flex items-center gap-4">
                    @if ($shop->shop_logo)
                        <img src="{{ Storage::url($shop->shop_logo) }}" alt="{{ $shop->shop_name }}"
                            class="w-16 h-16 rounded-full object-cover border-2 border-gray-300">
                    @else
                        <div
                            class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center border-2 border-gray-300">
                            <span
                                class="text-2xl font-medium text-white">{{ strtoupper(substr($shop->shop_name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold">{{ $shop->shop_name }}</h2>
                        <p class="text-sm">{{ $shop->shop_address_label }}</p>
                        <p class="text-sm mt-1">{{ $shop->description }}</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @php
                    $sortedProducts = $products->sortBy(function ($product) {
                        return $product->stock == 0 ? 1 : 0;
                    });
                @endphp
                @forelse ($sortedProducts as $product)
                    @if ($product->stock == 0)
                        <div
                            class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group h-full flex flex-col cursor-not-allowed">
                        @else
                            @if (auth()->check())
                                <a href="{{ route('products.show', $product->id) }}" class="block">
                                    <div
                                        class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group h-full flex flex-col">
                                    @else
                                        <div
                                            class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group h-full flex flex-col cursor-not-allowed">
                            @endif
                    @endif
                    <!-- Image Container -->
                    <div class="relative w-full h-32 sm:h-48 flex-shrink-0"> <!-- Changed height for mobile -->
                        @if ($product->images->isNotEmpty())
                            <img src="{{ Storage::url($product->images->first()->image_path) }}"
                                alt="{{ $product->name }}"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 {{ $product->stock == 0 ? 'opacity-50' : '' }}">
                        @else
                            <div
                                class="absolute inset-0 w-full h-full bg-gray-100 flex items-center justify-center {{ $product->stock == 0 ? 'opacity-50' : '' }}">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        @if ($product->stock == 0)
                            <div
                                class="absolute top-0 left-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-br">
                                Sold Out
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-3 flex flex-col flex-grow">
                        <!-- Shop Info -->
                        <div class="flex items-center gap-1 mb-1.5">
                            @if ($product->shop->shop_logo)
                                <img src="{{ Storage::url($product->shop->shop_logo) }}"
                                    alt="{{ $product->shop->shop_name }}" class="w-4 h-4 rounded-full">
                            @endif
                            <span class="text-xs text-gray-600 truncate">{{ $product->shop->shop_name }}</span>
                        </div>

                        <!-- Product Name -->
                        <h3 class="text-xs font-medium text-gray-800 line-clamp-2 min-h-[32px] mb-1.5">
                            {{ $product->name }}
                        </h3>

                        <!-- Badges -->
                        <div class="flex flex-wrap gap-1 mb-1.5">
                            <span class="px-1.5 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-medium rounded">
                                {{ $product->category->name }}
                            </span>
                            @if ($product->stock > 0)
                                <span
                                    class="px-1.5 py-0.5 {{ $product->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} text-[10px] font-medium rounded">
                                    {{ $product->stock }}
                                </span>
                            @endif
                        </div>

                        <!-- Price -->
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-1.5">
                                <p class="text-sm font-bold text-gray-800">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                @if ($product->discount_price)
                                    <p class="text-xs text-gray-500 line-through">
                                        Rp {{ number_format($product->original_price, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>

                            <!-- Rating -->
                            <div class="flex items-center gap-1">
                                <div class="flex items-center">
                                    @php
                                        $rating = $product->reviews()->avg('rating') ?? 0;
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
                                <span class="text-xs text-gray-600">({{ $product->reviews->count() }})</span>
                            </div>
                        </div>
                    </div>
                    @if ($product->stock != 0)

            </div>
            </a>
        @else
        </div>
        @endif
    @else
    </div>
    @endif
    @empty
        <div class="col-span-full text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada produk</h3>
            <p class="mt-1 text-sm text-gray-500">Belum ada produk yang tersedia saat ini.</p>
        </div>
        @endforelse
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
        </div>
        </div>
    </x-app-layout>
