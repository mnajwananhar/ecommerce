<x-app-layout>
    @section('title', 'Review') <!-- Title halaman Produk -->


    <div class="py-12 ">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-8">
                    Berikan Review Anda
                </h3>

                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="product_id" value="{{ $order->details->first()->product_id }}">

                    <!-- Rating Section -->
                    <div class="space-y-3">
                        <label class="block text-lg font-semibold text-gray-700">
                            Rating
                        </label>
                        <div class="flex items-center space-x-2">
                            <div class="flex space-x-1" id="star-rating-container">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="rating-{{ $i }}" name="rating"
                                        value="{{ $i }}" class="hidden peer" required />
                                    <label for="rating-{{ $i }}"
                                        class="star-label cursor-pointer transition-all duration-200"
                                        data-rating="{{ $i }}">
                                        <svg class="w-10 h-10 star-svg" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            <span id="rating-text" class="text-lg font-medium text-gray-600 ml-2">
                                0.0
                            </span>
                        </div>
                    </div>

                    <!-- Review Text Section -->
                    <div class="space-y-3">
                        <label for="review" class="block text-lg font-semibold text-gray-700">
                            Review
                        </label>
                        <textarea id="review" name="review" rows="5"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="Bagikan pengalaman Anda dengan produk ini..." required></textarea>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="space-y-3">
                        <label for="image" class="block text-lg font-semibold text-gray-700">
                            Upload Gambar (Opsional)
                        </label>
                        <div class="relative">
                            <input type="file" id="image" name="image" accept="image/*" class="hidden"
                                onchange="updateFileLabel(this)">
                            <label for="image"
                                class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-colors duration-200">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span id="file-label" class="text-gray-500">
                                        Pilih gambar atau drag & drop di sini
                                    </span>
                                </div>
                            </label>
                        </div>
                        <div id="image-preview" class="hidden mt-4">
                            <img id="preview-img" src="#" alt="Preview" class="max-w-xs rounded-lg shadow-md">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-[#FF9C08] text-white text-lg font-semibold rounded-lg hover:bg-[#e68a00] focus:outline-none focus:ring-2 focus:ring-[#FF9C08] focus:ring-offset-2 transition-all duration-200">
                            Kirim Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .star-svg {
            fill: #D1D5DB;
            /* Default gray color */
            transition: fill 0.2s ease;
        }

        .star-label:hover .star-svg,
        .star-label:hover~.star-label .star-svg {
            fill: #FBBF24;
            /* Hover yellow color */
        }

        input[type="radio"]:checked+.star-label .star-svg,
        input[type="radio"]:checked+.star-label~.star-label .star-svg {
            fill: #FBBF24;
            /* Selected yellow color */
        }
    </style>

    <script>
        // Star Rating Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('star-rating-container');
            const ratingText = document.getElementById('rating-text');
            const stars = container.querySelectorAll('.star-label');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.dataset.rating;
                    ratingText.textContent = `${rating}.0`;

                    // Reset all stars
                    stars.forEach(s => {
                        const starSvg = s.querySelector('.star-svg');
                        if (s.dataset.rating <= rating) {
                            starSvg.style.fill = '#FBBF24'; // Selected yellow
                        } else {
                            starSvg.style.fill = '#D1D5DB'; // Default gray
                        }
                    });
                });
            });
        });

        // Image Upload Preview
        function updateFileLabel(input) {
            const fileLabel = document.getElementById('file-label');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                fileLabel.textContent = fileName;

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                fileLabel.textContent = 'Pilih gambar atau drag & drop di sini';
                imagePreview.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
