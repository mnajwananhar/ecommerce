@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>
    @section('title', 'Order Details') <!-- Title halaman Produk -->


    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-8">
                <!-- Header Section -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                @if (auth()->user()->role === 'seller')
                                    Customer Order Details
                                @else
                                    Your Order Details
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Order ID: #{{ $order->id }}</p>
                        </div>
                        @if (auth()->user()->role === 'seller' && $order->status === 'pending')
                            <div class="flex space-x-2">
                                <form action="{{ route('orders.accept', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-green-500 text-white p-2 rounded hover:bg-green-600" title="Accept">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                                <form action="{{ route('orders.reject', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded hover:bg-red-600"
                                        title="Reject">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Shipping Information</h4>
                            <div class="space-y-2">
                                <p class="flex justify-between">
                                    <span class="text-gray-600">Order Date</span>
                                    <span class="font-medium">{{ $order->created_at->format('d-m-Y H:i') }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600">Courier</span>
                                    <span class="font-medium">{{ $order->courier }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600">Shipping Cost</span>
                                    <span class="font-medium">Rp
                                        {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Shipping Address</h4>
                            <p class="text-gray-800">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status and Total -->
                <div class="flex flex-wrap justify-between items-center mb-8 pb-6 border-b border-gray-200">
                    <div>
                        <span class="text-gray-600 mr-2">Status:</span>
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium
                            {{ $order->status === 'accepted'
                                ? 'bg-green-500 text-white'
                                : ($order->status === 'pending'
                                    ? 'bg-[#FF9C08] text-white'
                                    : 'bg-red-500 text-white') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="text-xl font-bold text-gray-900">
                        Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Product List -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Products in Order</h4>
                    <div class="space-y-6">
                        @foreach ($order->details as $detail)
                            <div class="flex flex-col md:flex-row gap-6 p-4 bg-gray-50 rounded-lg">
                                <!-- Product Image -->
                                <div class="w-full md:w-32 h-32 rounded-lg overflow-hidden flex-shrink-0">
                                    @if ($detail->product->images->isNotEmpty())
                                        <img src="{{ Storage::url($detail->product->images->first()->image_path) }}"
                                            alt="{{ $detail->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-400">No Image</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-grow space-y-2">
                                    <div class="flex justify-between items-start">
                                        <h5 class="text-lg font-medium text-gray-900">{{ $detail->product->name }}</h5>
                                        <p class="font-semibold text-gray-900">
                                            Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <p class="text-gray-600">
                                            <span class="font-medium">Unit Price:</span>
                                            Rp {{ number_format($detail->price, 0, ',', '.') }}
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-medium">Quantity:</span>
                                            {{ $detail->quantity }}
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-medium">Weight:</span>
                                            {{ $detail->product->weight }} grams
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-medium">Seller:</span>
                                            {{ $detail->product->seller->name ?? 'Unknown Seller' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Back Button -->
                <div class="flex justify-start">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center px-6 py-3 bg-[#FF9C08] text-white text-sm font-medium rounded-lg
                             hover:bg-[#e68a00] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF9C08]
                             transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
