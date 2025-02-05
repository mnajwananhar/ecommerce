<x-app-layout>
    @section('title', 'Shop Management') <!-- Title halaman Produk -->


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight mb-6" style="text-align: center">Shop Management
            </h2>
            <form action="{{ isset($shop) ? route('shops.update', $shop) : route('shops.store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-lg shadow-md">
                @csrf
                @if (isset($shop))
                    @method('POST')
                @endif

                <div>
                    <label for="shop_name" class="block text-gray-700">Shop Name</label>
                    <input type="text" id="shop_name" name="shop_name"
                        value="{{ $shop->shop_name ?? old('shop_name') }}" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label for="shop_address" class="block text-gray-700">Shop Address</label>
                    <input type="text" id="shop_address" oninput="searchDestination(this.value)"
                        class="w-full border rounded p-2" placeholder="Search address..." autocomplete="off" required>
                    <ul id="shop_address_list" class="bg-white shadow rounded hidden"></ul>
                    <input type="hidden" id="selected_shop_address" name="shop_address"
                        value="{{ $shop->shop_address ?? '' }}">
                    <input type="hidden" id="selected_shop_address_label" name="shop_address_label"
                        value="{{ $shop->shop_address_label ?? '' }}">
                </div>

                <div>
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full border rounded p-2" required>{{ $shop->description ?? old('description') }}</textarea>
                </div>

                <div>
                    <label for="shop_logo" class="block text-gray-700">Shop Logo</label>
                    <input type="file" id="shop_logo" name="shop_logo"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500 border-dashed"
                        onchange="previewImage(event)" required>
                    <div id="image_preview" class="mt-2"></div>
                </div>

                <button type="submit"
                    class="bg-[#FF9C08] hover:bg-[#e68a00] text-white px-4 py-2 rounded">Save</button>
            </form>
        </div>
    </div>

    <script>
        async function searchDestination(query) {
            const dropdown = document.getElementById('shop_address_list');
            const hiddenInput = document.getElementById('selected_shop_address');
            const hiddenLabel = document.getElementById('selected_shop_address_label');

            dropdown.innerHTML = '';
            if (!query || query.length < 3) {
                dropdown.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`{{ route('shops.search-destination') }}?search=${query}`);
                const data = await response.json();

                dropdown.innerHTML = '';
                data.forEach(dest => {
                    const li = document.createElement('li');
                    li.textContent = dest.label;
                    li.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100');
                    li.onclick = () => {
                        document.getElementById('shop_address').value = dest.label;
                        hiddenInput.value = dest.id;
                        hiddenLabel.value = dest.label;
                        dropdown.classList.add('hidden');
                    };
                    dropdown.appendChild(li);
                });

                dropdown.classList.remove('hidden');
            } catch (error) {
                console.error(error);
            }
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('image_preview');
                output.innerHTML =
                    `<img src="${reader.result}" alt="Selected Image" class="w-32 h-32 object-cover rounded-lg">`;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>
