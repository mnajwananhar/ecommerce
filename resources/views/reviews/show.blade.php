@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>

    @section('title', 'Review') <!-- Title halaman Produk -->



    <div class="py-12 ">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-8 space-y-8">
                <!-- Header with Back Button -->
                <div class="flex justify-between items-center border-b pb-4">
                    <h3 class="text-2xl font-bold text-gray-800">
                        Review Anda
                    </h3>
                    <a href="{{ route('orders.history') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>

                <!-- Rating Section -->
                <div class="space-y-3">
                    <h4 class="text-lg font-semibold text-gray-700">Rating</h4>
                    <div class="flex items-center space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rating)
                                <svg class="w-8 h-8 text-yellow-400 transition-colors duration-200"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-gray-300 transition-colors duration-200"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                </svg>
                            @endif
                        @endfor
                        <span class="ml-2 text-lg font-medium text-gray-700">
                            {{ $review->rating }}.0
                        </span>
                    </div>
                </div>

                <!-- Review Text Section -->
                <div class="space-y-3">
                    <h4 class="text-lg font-semibold text-gray-700">Review</h4>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $review->review }}
                        </p>
                    </div>
                </div>

                <!-- Image Section -->
                @if ($review->image)
                    <div class="space-y-3">
                        <h4 class="text-lg font-semibold text-gray-700">Gambar Review</h4>
                        <div class="relative group">
                            <img src="{{ Storage::url($review->image) }}" alt="Review Image"
                                class="rounded-lg shadow-md max-w-md w-full h-auto object-cover cursor-pointer transition-transform duration-300 group-hover:scale-[1.02]">
                            <div
                                class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 rounded-lg transition-opacity duration-300">
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Button -->
                <div class="pt-4 border-t">
                    <a href="{{ route('orders.history') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-[#FF9C08] text-white rounded-lg hover:bg-[#e68a00] focus:outline-none focus:ring-2 focus:ring-[#FF9C08] focus:ring-offset-2 transition-all duration-200">
                        <span class="mr-2">Kembali ke Riwayat Pesanan</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
