@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    @section('title', 'Seller Applications') <!-- Title halaman Produk -->


    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight text-gray-900 mb-6">
                {{ __('Seller Applications') }}
            </h2>

            <div class="bg-white shadow-md rounded-lg p-4 sm:p-6 space-y-4 sm:space-y-6">
                @forelse ($requests as $request)
                    <div
                        class="p-3 sm:p-4 bg-gray-50 rounded-md shadow-sm flex flex-col sm:flex-row
                             items-start sm:items-center gap-4 sm:gap-6">
                        <!-- Selfie Photo -->
                        <div class="w-24 h-24 sm:w-20 sm:h-20 lg:w-24 lg:h-24 overflow-hidden rounded-md flex-shrink-0 cursor-pointer mx-auto sm:mx-0"
                            onclick="openImageModal('{{ Storage::url($request->selfie_photo) }}')">
                            <img src="{{ Storage::url($request->selfie_photo) }}"
                                alt="Selfie Photo of {{ $request->full_name }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-200"
                                onerror="this.src='/images/default-avatar.png'">
                        </div>

                        <!-- Application Info -->
                        <div class="flex-1 w-full sm:w-auto">
                            <p class="text-gray-800 text-sm sm:text-base mb-1">
                                <strong>Name:</strong> {{ $request->full_name }}
                            </p>
                            <p class="text-gray-800 text-sm sm:text-base">
                                <strong>ID Number:</strong> {{ $request->nik }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 w-full sm:w-auto">
                            <form action="{{ route('seller-requests.approve', $request) }}" method="POST"
                                class="w-1/2 sm:w-auto">
                                @csrf
                                <button type="submit"
                                    class="w-full sm:w-auto bg-green-500 text-white px-4 py-2 rounded text-xs sm:text-sm 
                                           hover:bg-green-600 transition-colors duration-200">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('seller-requests.reject', $request) }}" method="POST"
                                class="w-1/2 sm:w-auto">
                                @csrf
                                <button type="submit"
                                    class="w-full sm:w-auto bg-red-500 text-white px-4 py-2 rounded text-xs sm:text-sm
                                           hover:bg-red-600 transition-colors duration-200">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-sm sm:text-base">No pending applications to process.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Improved Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black/75 z-50 hidden items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="max-w-4xl w-full relative">
            <img id="modalImage" src="" alt="Zoomed photo"
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
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
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
