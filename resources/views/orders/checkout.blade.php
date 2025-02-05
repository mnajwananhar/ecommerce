@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>
    @section('title', 'Checkout') <!-- Title halaman Produk -->


    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Checkout') }}
                </h2>
                <p class="text-sm text-gray-600">Complete your purchase securely</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kolom Kiri - Detail Produk -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <!-- Header Produk -->
                        <div class="border-b border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900">
                                Order Details
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Review your order
                            </p>
                        </div>

                        <!-- Daftar Produk -->
                        <div class="p-6 space-y-4">
                            @foreach ($cartItems as $item)
                                <div class="flex items-center space-x-4 py-4 border-b border-gray-100 last:border-0">
                                    <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                        <!-- Placeholder untuk gambar produk -->
                                        <img src="{{ $item->product->images->count() > 0
                                            ? Storage::url($item->product->images->first()->image_path)
                                            : Storage::url('placeholder.png') }}"
                                            alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-medium text-gray-900 truncate">
                                            {{ $item->product->name }}
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Qty: {{ $item->quantity }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-base font-medium text-gray-900">
                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Form Pengiriman -->
                    <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="border-b border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900">
                                Shipping Information
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Please enter a valid shipping address
                            </p>
                        </div>

                        <form id="checkout-form" class="p-6 space-y-6">
                            @csrf
                            <input type="hidden" id="origin" name="origin" value="{{ $origin }}">

                            <!-- Kota Tujuan -->
                            <div>
                                <label for="destination" class="block text-sm font-medium text-gray-700">
                                    Destination City
                                </label>
                                <div class="mt-1 relative">
                                    <input type="text" id="destination" name="destination"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        placeholder="Enter city name" oninput="searchDestination(this.value)" required>
                                    <ul id="destination-dropdown"
                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm hidden">
                                    </ul>
                                    <input type="hidden" id="destination_id" name="destination_id">
                                </div>
                            </div>

                            <!-- Berat Total -->
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">
                                    Total Weight
                                </label>
                                <div class="mt-1">
                                    <input type="number" id="weight" name="weight" value="{{ $totalWeight }}"
                                        class="block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                                        readonly>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Total weight in grams</p>
                            </div>

                            <!-- Kurir -->
                            <div id="courier-container" style="display: none;">
                                <label for="courier" class="block text-sm font-medium text-gray-700">
                                    Select Courier
                                </label>
                                <div class="mt-1">
                                    <select id="courier" name="courier"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">Select Shipping Courier</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kolom Kanan - Ringkasan -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden sticky top-6">
                        <div class="border-b border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900">
                                Order Summary
                            </h3>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Subtotal -->
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">
                                    Rp
                                    {{ number_format(
                                        $cartItems->sum(function ($item) {
                                            return $item->product->price * $item->quantity;
                                        }),
                                        0,
                                        ',',
                                        '.',
                                    ) }}
                                </span>
                            </div>

                            <!-- Ongkos Kirim -->
                            <div id="shipping-cost-display" class="flex justify-between text-sm" style="display: none;">
                                <span class="text-gray-600">Shipping Cost</span>
                                <span class="font-medium text-gray-900" id="shipping-cost-amount">-</span>
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between">
                                    <span class="text-base font-medium text-gray-900">Total</span>
                                    <span class="text-base font-medium text-gray-900" id="total-amount">
                                        Rp
                                        {{ number_format(
                                            $cartItems->sum(function ($item) {
                                                return $item->product->price * $item->quantity;
                                            }),
                                            0,
                                            ',',
                                            '.',
                                        ) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Includes taxes and shipping</p>
                            </div>

                            <!-- Hasil Shipping -->
                            <div id="shipping-result" class="hidden mt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Shipping Options:</h4>
                                <ul id="shipping-options" class="space-y-2"></ul>
                            </div>

                            <!-- Tombol Checkout -->
                            <button type="button" onclick="calculateShipping()"
                                class="w-full mt-6 bg-[#FF9C08] border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-[#e68a00] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Calculate Shipping
                            </button>

                            <button type="button" onclick="saveOrder()"
                                class="w-full mt-3 bg-green-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hidden"
                                id="save-order-btn">
                                Pay Now
                            </button>
                            <div id="error-message" class="text-red-500 hidden"></div>
                            <div id="success-message" class="text-green-500 hidden"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.snap === 'undefined') {
                console.error('Midtrans Snap tidak terdeteksi!');
            } else {
                console.log('Midtrans Snap berhasil dimuat');
            }
        });
        let selectedDestination = null;
        let selectedCourier = null;
        let selectedCost = null;

        async function searchDestination(query) {
            const dropdown = document.getElementById('destination-dropdown');
            dropdown.innerHTML = '';
            dropdown.classList.add('hidden');

            if (query.length < 3) return;

            try {
                const response = await fetch(`/search-destination?search=${query}`);
                const data = await response.json();

                data.forEach(item => {
                    const li = document.createElement('li');
                    li.textContent = item.label;
                    li.dataset.id = item.id;
                    li.classList.add('cursor-pointer', 'p-2', 'hover:bg-gray-100');
                    li.onclick = () => {
                        document.getElementById('destination').value = item.label;
                        document.getElementById('destination_id').value = item.id;
                        selectedDestination = item.id;
                        dropdown.classList.add('hidden');
                        checkAvailableCouriers();
                    };
                    dropdown.appendChild(li);
                });

                dropdown.classList.remove('hidden');
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function checkAvailableCouriers() {
            const origin = document.getElementById('origin').value;
            const destination = document.getElementById('destination_id').value;
            const weight = document.getElementById('weight').value;

            if (!destination || !origin || !weight) {
                return;
            }

            // Tampilkan container kurir
            const courierContainer = document.getElementById('courier-container');
            courierContainer.style.display = 'block';

            // Isi dropdown dengan opsi kurir yang tersedia
            const select = document.getElementById('courier');
            select.innerHTML = '<option value="">Select Shipping Courier</option>';

            // Daftar kurir yang umum digunakan
            const couriers = [{
                    code: 'jne',
                    name: 'JNE'
                },
                {
                    code: 'pos',
                    name: 'POS Indonesia'
                },
                {
                    code: 'tiki',
                    name: 'TIKI'
                },
                {
                    code: 'sicepat',
                    name: 'SiCepat'
                },
                {
                    code: 'jnt',
                    name: 'J&T Express'
                },
                {
                    code: 'anteraja',
                    name: 'AnterAja'
                }
            ];

            // Tambahkan semua kurir ke dropdown
            couriers.forEach(courier => {
                const option = document.createElement('option');
                option.value = courier.code;
                option.textContent = courier.name;
                select.appendChild(option);
            });

            // Tambahkan event listener untuk perubahan kurir
            select.addEventListener('change', function() {
                if (this.value) {
                    document.querySelector('button[onclick="calculateShipping()"]').disabled = false;
                }
            });
        }

        async function calculateShipping() {
            const origin = document.getElementById('origin').value;
            const destination = document.getElementById('destination_id').value;
            const weight = document.getElementById('weight').value;
            const courier = document.getElementById('courier').value;

            if (!destination || !origin || !weight || !courier) {
                showError('Mohon lengkapi semua data pengiriman!');
                return;
            }

            try {
                const response = await fetch('{{ route('calculate.shipping') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        origin: origin.toString(),
                        destination: destination.toString(),
                        weight: weight,
                        courier: courier
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    const shippingOptions = document.getElementById('shipping-options');
                    shippingOptions.innerHTML = '';

                    if (result.data && result.data.length > 0) {
                        result.data.forEach(option => {
                            const li = document.createElement('li');
                            li.innerHTML = `
                        <div class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200"
                             onclick="selectCourier('${option.name} (${option.service})', ${option.cost})">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">${option.name} (${option.service})</p>
                                <p class="text-sm text-gray-500">${option.description}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-sm font-medium text-gray-900">Rp ${option.cost.toLocaleString('id-ID')}</span>
                                    <span class="mx-2 text-gray-400">•</span>
                                    <span class="text-sm text-gray-500">Estimasi ${option.etd}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                            </div>
                        </div>
                    `;
                            shippingOptions.appendChild(li);
                        });

                        document.getElementById('shipping-result').classList.remove('hidden');
                        document.getElementById('save-order-btn').classList.remove('hidden');
                    } else {
                        showError(
                            'Tidak ada layanan pengiriman yang tersedia untuk rute ini dengan kurir yang dipilih');
                    }
                } else {
                    showError('Terjadi kesalahan saat menghitung ongkir. Silakan coba lagi.');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menghitung ongkir. Silakan coba lagi.');
            }
        }

        async function calculateShipping() {
            const origin = document.getElementById('origin').value;
            const destination = document.getElementById('destination_id').value;
            const weight = document.getElementById('weight').value;
            const courier = document.getElementById('courier').value;

            if (!destination || !origin || !weight || !courier) {
                showError('Mohon lengkapi semua data pengiriman!');
                return;
            }

            try {
                const response = await fetch('{{ route('calculate.shipping') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        origin: origin.toString(),
                        destination: destination.toString(),
                        weight: weight,
                        courier: courier,
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    const shippingOptions = document.getElementById('shipping-options');
                    shippingOptions.innerHTML = '';

                    result.data.forEach(option => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                            <div class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200"
                                 onclick="selectCourier('${option.name} (${option.service})', ${option.cost})">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">${option.name} (${option.service})</p>
                                    <p class="text-sm text-gray-500">${option.description}</p>
                                    <div class="flex items-center mt-1">
                                        <span class="text-sm font-medium text-gray-900">Rp ${option.cost.toLocaleString('id-ID')}</span>
                                        <span class="mx-2 text-gray-400">•</span>
                                        <span class="text-sm text-gray-500">Estimasi ${option.etd}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                                </div>
                            </div>
                        `;
                        shippingOptions.appendChild(li);
                    });

                    document.getElementById('shipping-result').classList.remove('hidden');
                    document.getElementById('save-order-btn').classList.remove('hidden');
                } else {
                    showError('Terjadi kesalahan saat menghitung ongkir. Silakan coba lagi.');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menghitung ongkir. Silakan coba lagi.');
            }
        }

        function selectCourier(courier, cost) {
            // Update state
            selectedCourier = courier;
            selectedCost = cost;

            // Update tampilan
            const options = document.querySelectorAll('#shipping-options > li > div');
            options.forEach(option => {
                const radio = option.querySelector('.w-4');
                if (option.textContent.includes(courier)) {
                    radio.classList.add('bg-blue-500', 'border-blue-500');
                } else {
                    radio.classList.remove('bg-blue-500', 'border-blue-500');
                }
            });

            // Update ringkasan biaya
            document.getElementById('shipping-cost-display').style.display = 'flex';
            document.getElementById('shipping-cost-amount').textContent = `Rp ${cost.toLocaleString('id-ID')}`;

            // Hitung total
            const subtotal =
                {{ $cartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                }) }};
            const total = subtotal + cost;
            document.getElementById('total-amount').textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }

        async function saveOrder() {
            // Definisikan requestData di sini
            const shippingAddress = document.getElementById('destination').value;
            const cartItems = @json($cartItems);

            if (!shippingAddress || !selectedCourier || !selectedCost) {
                showError('Mohon lengkapi data pengiriman terlebih dahulu!');
                return;
            }

            // Buat objek requestData
            const requestData = {
                cart: cartItems.map(item => ({
                    product_id: item.product.id,
                    name: item.product.name,
                    price: item.product.price,
                    quantity: item.quantity,
                })),
                shipping_address: shippingAddress,
                shipping_cost: selectedCost,
                courier: selectedCourier,
            };

            try {
                // Disable tombol untuk mencegah double submit
                const saveButton = document.getElementById('save-order-btn');
                saveButton.disabled = true;
                saveButton.textContent = 'Processing...';

                const response = await fetch('{{ route('orders.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(requestData),
                });

                const result = await response.json();

                if (result.success) {
                    // Check if snap is initialized
                    if (typeof window.snap === 'undefined') {
                        console.error('Snap belum diinisialisasi!');
                        showError('Terjadi kesalahan: Sistem pembayaran belum siap');
                        saveButton.disabled = false;
                        saveButton.textContent = 'Bayar Sekarang';
                        return;
                    }

                    window.snap.pay(result.snap_token, {
                        onSuccess: function(result) {
                            showSuccess('Pembayaran berhasil!');
                            window.location.href = '/orders/history';
                        },
                        onPending: function(result) {
                            alert('Silakan selesaikan pembayaran Anda');
                            window.location.href = '/orders/history';
                        },
                        onError: function(result) {
                            showError('Pembayaran gagal! Silakan coba lagi');
                            saveButton.disabled = false;
                            saveButton.textContent = 'Bayar Sekarang';
                        },
                        onClose: function() {
                            alert('Anda menutup popup pembayaran sebelum menyelesaikan transaksi');
                            saveButton.disabled = false;
                            saveButton.textContent = 'Bayar Sekarang';
                        }
                    });
                } else {
                    showError('Terjadi kesalahan saat menyimpan pesanan. Silakan coba lagi.');
                    saveButton.disabled = false;
                    saveButton.textContent = 'Bayar Sekarang';
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Terjadi kesalahan. Silakan coba lagi');
                saveButton.disabled = false;
                saveButton.textContent = 'Bayar Sekarang';
            }
        }

        function showError(message) {
            const errorMessage = document.getElementById('error-message');
            errorMessage.textContent = message;
            errorMessage.classList.remove('hidden');
        }

        function showSuccess(message) {
            const successMessage = document.getElementById('success-message');
            successMessage.textContent = message;
            successMessage.classList.remove('hidden');
        }

        // Initialize tooltips and other UI elements
        document.addEventListener('DOMContentLoaded', function() {
            // Hide shipping cost display initially
            document.getElementById('shipping-cost-display').style.display = 'none';
        });
    </script>
</x-app-layout>
