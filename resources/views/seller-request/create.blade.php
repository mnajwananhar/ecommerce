@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    @section('title', 'Apply to Become a Seller') <!-- Title halaman Produk -->



    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight px-4 sm:px-0 mb-4">
                {{ __('Apply to Become a Seller') }}
            </h2>
            <div class="bg-white shadow-xl rounded-lg p-4 sm:p-6 space-y-4 sm:space-y-6">
                @if ($status === 'pending')
                    <div class="flex flex-col items-center text-center space-y-3 sm:space-y-4">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-yellow-500" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825a4.992 4.992 0 01-3.75 0M9 10h.01M15 10h.01M9.093
                                   15h5.814M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477
                                   2 12s4.477 10 10 10z" />
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 px-2">
                            Your Application is Being Processed
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed max-w-md px-2">
                            Please wait for admin approval. We will notify you once your application
                            has been approved or rejected.
                        </p>
                    </div>
                @elseif ($status === 'rejected')
                    <div class="flex flex-col items-center text-center space-y-3 sm:space-y-4">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-500" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2
                                   2l2 2m9-2a9 9 0 11-18 0 9 9
                                   0 0118 0z" />
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 px-2">
                            Your Application Has Been Rejected
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed max-w-md px-2">
                            We're sorry, your seller application has been rejected.
                            Please review your data and submit again.
                        </p>
                        <form action="{{ route('seller-request.reset') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full sm:w-auto bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 
                                       transition-colors text-sm sm:text-base">
                                Apply Again
                            </button>
                        </form>
                    </div>
                @elseif ($status === 'approved')
                    @php
                        $shop = auth()->user()->shop; // Assuming the shop is related to the authenticated user
                    @endphp
                    @if ($shop)
                        <script>
                            window.location.href = "{{ route('shops.edit', ['shop' => $shop->id]) }}";
                        </script>
                    @else
                        <div class="flex flex-col items-center text-center space-y-3 sm:space-y-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 px-2">
                                Shop not found
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed max-w-md px-2">
                                We couldn't find your shop. Please contact support for assistance.
                            </p>
                        </div>
                    @endif
                @else
                    <form action="{{ route('seller-request.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-4 sm:space-y-6 max-w-2xl mx-auto">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <!-- ID Number -->
                            <div>
                                <label for="nik"
                                    class="block text-gray-700 text-sm sm:text-base font-semibold mb-1">
                                    ID Number
                                </label>
                                <input type="text" id="nik" name="nik"
                                    class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base
                                           focus:outline-none focus:border-blue-500"
                                    required>
                            </div>

                            <!-- Full Name -->
                            <div>
                                <label for="full_name"
                                    class="block text-gray-700 text-sm sm:text-base font-semibold mb-1">
                                    Full Name
                                </label>
                                <input type="text" id="full_name" name="full_name"
                                    class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base
                                           focus:outline-none focus:border-blue-500"
                                    required>
                            </div>
                        </div>

                        <!-- Selfie Photo -->
                        <div>
                            <label for="selfie_photo"
                                class="block text-gray-700 text-sm sm:text-base font-semibold mb-1">
                                Selfie Photo
                            </label>
                            <div class="flex flex-col sm:flex-row gap-4 items-start">
                                <div class="w-full sm:w-64">
                                    <input type="file" id="selfie_photo" name="selfie_photo"
                                        class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-xs sm:text-sm
                                               focus:outline-none focus:border-blue-500 file:mr-4 file:py-2 file:px-4
                                               file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700
                                               hover:file:bg-blue-100"
                                        accept="image/*" required onchange="previewImage(this)">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Accepted formats: JPG, PNG. Max size: 2MB
                                    </p>
                                </div>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="hidden">
                                    <div class="w-24 h-24 sm:w-32 sm:h-32 overflow-hidden rounded-lg cursor-pointer"
                                        onclick="openImageModal()">
                                        <img id="preview" src="" alt="Preview"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full sm:w-auto bg-[#FF9C08] text-white px-6 py-2 rounded hover:bg-[#FFB74D]
                                       transition-colors duration-200 focus:outline-none text-sm sm:text-base">
                                Submit
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Improved Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black/75 z-50 hidden items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="max-w-4xl w-full relative">
            <img id="modalImage" src="" alt="Preview photo"
                class="w-full h-auto max-h-[80vh] object-contain rounded-lg">
            <button onclick="closeImageModal()"
                class="absolute -top-2 -right-2 text-white bg-black/50 rounded-full p-2
                           hover:bg-black/75 transition-colors focus:outline-none">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <script>
        async function searchDestination(query) {
            const dropdown = document.getElementById('shop_address_list');
            const hiddenInput = document.getElementById('selected_shop_address');
            const hiddenLabel = document.getElementById('selected_shop_address_label');
            const inputElement = document.getElementById('shop_address');

            dropdown.innerHTML = '';
            if (!query || query.length < 3) {
                dropdown.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`/seller-request/destination?search=${query}`);
                const data = await response.json();

                dropdown.innerHTML = '';
                data.forEach(destination => {
                    const li = document.createElement('li');
                    li.textContent = destination.label;
                    li.classList.add('cursor-pointer', 'p-2', 'hover:bg-gray-100');
                    li.onclick = () => {
                        inputElement.value = destination.label;
                        hiddenInput.value = destination.id; // Simpan ID ke hidden input
                        hiddenLabel.value = destination.label; // Simpan label ke hidden input
                        dropdown.classList.add('hidden');
                    };
                    dropdown.appendChild(li);
                });

                if (data.length > 0) {
                    dropdown.classList.remove('hidden');
                } else {
                    dropdown.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error fetching destinations:', error);
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewDiv.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function openImageModal() {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const preview = document.getElementById('preview');
            modalImage.src = preview.src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>
