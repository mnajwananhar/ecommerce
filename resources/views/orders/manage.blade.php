<x-app-layout>
    @section('title', 'Manage Orders') <!-- Title halaman Produk -->

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Incoming Orders</h3>

                @if ($orders->isEmpty())
                    <p class="text-gray-500">There are no orders to manage.</p>
                @else
                    <!-- Order Card Container -->
                    <div class="space-y-6">
                        @foreach ($orders as $order)
                            <!-- Order Card -->
                            <div class="bg-gray-50 rounded-md shadow-sm p-4">
                                <!-- Top Section: Order Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">
                                            Order #{{ $order->id }}
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            Date: {{ $order->created_at->format('d-m-Y H:i') }}
                                        </p>
                                        <!-- Order Status -->
                                        <p class="text-sm">
                                            <strong>Status:</strong>
                                            <span
                                                class="px-2 py-1 text-sm rounded-full
                                                @if ($order->status === 'pending') bg-[#FF9C08] text-white
                                                @elseif ($order->status === 'accepted') bg-green-500 text-white
                                                @else bg-red-500 text-white @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </p>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2">
                                        <!-- Detail Button always displayed -->
                                        <a href="{{ route('orders.details', $order->id) }}"
                                            class="bg-[#FF9C08] text-white px-4 py-2 rounded hover:bg-[#e68a00] text-sm">
                                            Order Details
                                        </a>

                                        @if ($order->status === 'pending')
                                            <form action="{{ route('orders.accept', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-green-500 text-white p-2 rounded hover:bg-green-600"
                                                    title="Accept">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('orders.reject', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-red-500 text-white p-2 rounded hover:bg-red-600"
                                                    title="Reject">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 111.414 1.414L11.414 10l4.293 4.293a1 1 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 01-1.414-1.414L8.586 10 4.293 5.707a1 1 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <!-- Middle Section: Order Details -->
                                <div class="space-y-2 mb-4">
                                    <p class="text-sm text-gray-700">
                                        <strong>Shipping Address:</strong> {{ $order->shipping_address }}
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        <strong>Courier:</strong> {{ $order->courier }}
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        <strong>Shipping Cost:</strong>
                                        Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        <strong>Total Price:</strong>
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Product List -->
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-800 mb-2">Ordered Products:</h5>
                                    <ul class="space-y-1 ml-4 list-disc list-inside">
                                        @foreach ($order->details as $detail)
                                            <li class="text-sm text-gray-700">
                                                {{ $detail->product->name }}
                                                <span class="text-gray-500">x {{ $detail->quantity }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Add pagination links -->
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
