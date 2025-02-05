@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>

    @section('title', 'Cart') <!-- Title halaman Produk -->


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($cartItems->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">Your shopping cart is empty.</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-block bg-[#FF9C08] text-white px-4 py-2 rounded 
                                  hover:bg-[#e68a00] transition-colors duration-200">
                            Start Shopping
                        </a>
                    </div>
                @else
                    <!-- Product List in Cart -->
                    <div class="space-y-4">
                        @foreach ($cartItems as $item)
                            <div class="flex flex-col sm:flex-row p-4 bg-gray-50 rounded-lg shadow-sm">
                                <!-- Product Image -->
                                <div class="w-full sm:w-32 h-32 mb-4 sm:mb-0 sm:mr-4">
                                    @php
                                        $imagePath =
                                            optional($item->product->images->first())->image_path ?? 'default.png';
                                    @endphp
                                    <img src="{{ Storage::url($imagePath) }}" alt="{{ $item->product->name }}"
                                        class="w-full h-full object-cover rounded-lg">
                                </div>

                                <!-- Product Information -->
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-800 mb-1">
                                                {{ $item->product->name }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                Price: Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <!-- Remove Button -->
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                            class="sm:ml-4">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-600 p-1 rounded-full hover:bg-red-50 transition-all duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Quantity Selector -->
                                    <div class="mt-4">
                                        <label class="text-sm text-gray-600 mb-1 block">
                                            Quantity:
                                        </label>
                                        <div class="flex items-center">
                                            <div class="flex items-center border rounded">
                                                <button type="button"
                                                    onclick="updateQuantity({{ $item->id }}, 'decrease')"
                                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 
                                                               border-r transition-colors duration-200">
                                                    -
                                                </button>
                                                <input type="number" value="{{ $item->quantity }}"
                                                    class="w-20 text-center border-none focus:ring-0"
                                                    id="quantity-{{ $item->id }}" readonly>
                                                <button type="button"
                                                    onclick="updateQuantity({{ $item->id }}, 'increase')"
                                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 
                                                               border-l transition-colors duration-200">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2">
                                            Stock available: {{ $item->product->stock }}
                                        </p>
                                        <p class="font-semibold text-sm text-gray-800 mt-2"
                                            id="subtotal-{{ $item->id }}">
                                            Subtotal: Rp
                                            {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </p>
                                        <p class="text-red-500 text-sm mt-2" id="error-{{ $item->id }}"></p>
                                    </div>
                                    <input type="hidden" id="stock-{{ $item->id }}"
                                        value="{{ $item->product->stock }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total & Checkout -->
                    <div class="mt-8 border-t pt-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center">
                            <p class="text-lg font-semibold text-gray-800 mb-4 sm:mb-0" id="cart-total">
                                Total Price: Rp {{ number_format($cartTotal, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('orders.checkout') }}"
                                class="w-full sm:w-auto bg-[#FF9C08] text-white px-6 py-3 
                                      rounded-lg hover:bg-[#e68a00] transition-colors 
                                      duration-200 text-center">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function updateQuantity(itemId, action) {
            const input = document.getElementById(`quantity-${itemId}`);
            let newQuantity = parseInt(input.value);

            if (action === 'increase') {
                newQuantity += 1;
            } else if (action === 'decrease' && newQuantity > 1) {
                newQuantity -= 1;
            }

            if (newQuantity < 1) return;

            // Get the product's stock
            const productStock = parseInt(document.getElementById(`stock-${itemId}`).value);

            // Check if the new quantity exceeds the stock
            const errorElement = document.getElementById(`error-${itemId}`);
            if (newQuantity > productStock) {
                errorElement.textContent = 'The purchased quantity exceeds the product stock.';
                return;
            } else {
                errorElement.textContent = '';
            }

            // Show loading state
            const subtotalElement = document.getElementById(`subtotal-${itemId}`);
            subtotalElement.innerHTML += ' <span class="text-gray-500">(Updating...)</span>';

            fetch(`/cart/${itemId}/update-quantity`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update quantity
                        input.value = data.newQuantity;

                        // Update subtotal
                        subtotalElement.textContent =
                            `Subtotal: Rp ${new Intl.NumberFormat('id-ID').format(data.subtotal)}`;

                        // Update cart total
                        document.getElementById('cart-total').textContent =
                            `Total Price: Rp ${new Intl.NumberFormat('id-ID').format(data.cartTotal)}`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    subtotalElement.textContent = 'Error updating quantity. Please try again.';
                });
        }
    </script>
</x-app-layout>
