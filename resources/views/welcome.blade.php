@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    @section('title', 'All Products - E-Commerce') <!-- Title halaman Produk -->
    <!-- Products Grid Section -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Banner Slider Section -->
            <div x-data="carousel()"
                class="relative bg-gradient-to-r from-[#FF9C08] to-[#FFB347] bg-opacity-30 backdrop-blur-md rounded-lg py-8 md:py-12 shadow-md overflow-hidden">
                <!-- Slider Container -->
                <div class="flex transition-transform duration-500"
                    :style="`transform: translateX(-${currentIndex * 100}%);`">
                    <!-- Slide Template -->
                    <template x-for="(slide, index) in slides" :key="index">
                        <div class="min-w-full flex flex-col items-center justify-center text-center px-4">
                            <!-- Judul slider: ukuran teks responsif -->
                            <h2 class="text-2xl md:text-4xl font-bold text-white" x-text="slide.title"></h2>
                            <!-- Deskripsi slider: ukuran teks responsif -->
                            <p class="mt-4 text-sm md:text-xl text-white" x-text="slide.description"></p>
                        </div>
                    </template>
                </div>

                <!-- Tombol Navigasi -->
                <button @click="prev()"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 text-white p-2 rounded-full">
                    <!-- Icon panah kiri -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button @click="next()"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 text-white p-2 rounded-full">
                    <!-- Icon panah kanan -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 mt-8">
                @forelse ($products->filter(fn($product) => $product->stock > 0) as $product)
                    <a href="{{ route('products.show', $product->id) }}" class="block">
                        <div
                            class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group h-full flex flex-col">
                            <!-- Image Container -->
                            <div class="relative w-full h-32 sm:h-48 flex-shrink-0"> <!-- Changed height for mobile -->
                                @if ($product->images->isNotEmpty())
                                    <img src="{{ Storage::url($product->images->first()->image_path) }}"
                                        alt="{{ $product->name }}"
                                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div
                                        class="absolute inset-0 w-full h-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
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
                                    <span
                                        class="px-1.5 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-medium rounded">
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
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
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

<!-- Alpine.js Carousel Script -->
<script>
    function carousel() {
        return {
            currentIndex: 0,
            slides: [{
                    title: 'Welcome to Our E-Commerce Platform',
                    description: 'Discover the best products at unbeatable prices!'
                },
                {
                    title: 'Shop Smart, Live Better',
                    description: 'Enjoy exclusive deals and discounts that you can’t miss!'
                },
                {
                    title: 'Latest Promotions',
                    description: 'Experience seamless online shopping with amazing offers!'
                }
            ],
            prev() {
                this.currentIndex = (this.currentIndex === 0) ? this.slides.length - 1 : this.currentIndex - 1;
            },
            next() {
                this.currentIndex = (this.currentIndex === this.slides.length - 1) ? 0 : this.currentIndex + 1;
            }
        }
    }
</script>
