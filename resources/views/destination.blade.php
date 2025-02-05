<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cari Destinasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Form Input Pencarian -->
                <form>
                    <div class="mb-4">
                        <label for="search" class="block text-gray-700 font-bold">Cari Destinasi</label>
                        <input type="text" id="search" name="search"
                            placeholder="Masukkan nama kota atau kabupaten"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </form>

                <!-- Dropdown Hasil Pencarian -->
                <div class="mt-4">
                    <label for="destination-dropdown" class="block text-gray-700 font-bold">Hasil Pencarian</label>
                    <select id="destination-dropdown"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Pilih Destinasi</option>
                    </select>
                </div>

                <!-- Pesan Error -->
                <p id="error-message" style="color: red; display: none;" class="mt-4">
                    Gagal memuat data destinasi.
                </p>

                <!-- Tampilkan ID yang Dipilih -->
                <div class="mt-4">
                    <p id="selected-id" class="text-gray-700 font-bold">ID yang dipilih: -</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Event listener untuk input pencarian
        document.getElementById('search').addEventListener('input', async function() {
            const searchValue = this.value; // Ambil nilai input
            const errorMessage = document.getElementById('error-message');
            const dropdown = document.getElementById('destination-dropdown');

            // Reset dropdown dan pesan error
            dropdown.innerHTML = '<option value="">Pilih Destinasi</option>';
            errorMessage.style.display = 'none';

            // Cegah pencarian jika input kurang dari 3 karakter
            if (searchValue.length < 3) return;

            try {
                // Fetch data dari API
                const response = await fetch(`/search-destination?search=${searchValue}`);
                if (!response.ok) throw new Error('Gagal memuat data');

                const data = await response.json();

                // Tambahkan hasil ke dropdown
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id; // Gunakan ID sebagai value
                    option.textContent = item.label;
                    dropdown.appendChild(option);
                });
            } catch (error) {
                errorMessage.style.display = 'block'; // Tampilkan pesan error
            }
        });

        // Event listener untuk dropdown perubahan pilihan
        document.getElementById('destination-dropdown').addEventListener('change', function() {
            const selectedId = this.value; // Ambil ID dari value dropdown
            const selectedIdDisplay = document.getElementById('selected-id');

            // Tampilkan ID yang dipilih di halaman
            selectedIdDisplay.textContent = `ID yang dipilih: ${selectedId || '-'}`;

            // Log ID ke console
            console.log(`ID yang dipilih: ${selectedId}`);
        });
    </script>
</x-app-layout>
