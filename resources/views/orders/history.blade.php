@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>
    @section('title', 'Order History') <!-- Title halaman Produk -->


    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($orders->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Orders Yet</h3>
                        <p class="mt-2 text-gray-500">You do not have any order history at the moment.</p>
                        <a href="{{ route('products.index') }}"
                            class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Start Shopping
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-2 sm:space-y-3">
                    @foreach ($orders as $order)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="border-b border-gray-100 bg-white px-3 py-2 sm:px-4 sm:py-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex flex-col">
                                            <span class="text-xs sm:text-sm font-medium text-gray-900">Order
                                                #{{ $order->id }}</span>
                                            <span
                                                class="text-[11px] sm:text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-[11px] sm:text-xs font-medium
                                        {{ $order->status === 'accepted'
                                            ? 'bg-green-500 text-white'
                                            : ($order->status === 'pending'
                                                ? 'bg-[#FF9C08] text-white'
                                                : 'bg-red-500 text-white') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Products List -->
                            <div class="px-3 py-2 sm:px-4">
                                <div class="flow-root">
                                    <ul role="list" class="-my-2 divide-y divide-gray-100">
                                        @foreach ($order->details as $detail)
                                            <li class="flex py-2">
                                                <div
                                                    class="flex-shrink-0 rounded-md overflow-hidden w-14 h-14 sm:w-16 sm:h-16 border border-gray-200">
                                                    @if ($detail->product->images->isNotEmpty())
                                                        <img src="{{ Storage::url($detail->product->images->first()->image_path) }}"
                                                            alt="{{ $detail->product->name }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <div
                                                            class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-gray-300" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <div class="flex items-start justify-between">
                                                        <h4
                                                            class="text-xs sm:text-sm font-medium text-gray-900 line-clamp-2">
                                                            {{ $detail->product->name }}</h4>
                                                        <p
                                                            class="text-xs sm:text-sm font-medium text-gray-900 ml-2 whitespace-nowrap">
                                                            Rp
                                                            {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                    <p class="mt-0.5 text-[11px] sm:text-xs text-gray-500">Qty:
                                                        {{ $detail->quantity }}</p>
                                                    <p class="text-[11px] sm:text-xs text-gray-500">
                                                        {{ $detail->product->shop->shop_name }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="bg-gray-50 px-3 py-2 sm:px-4 sm:py-3">
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] sm:text-xs text-gray-600">Subtotal</span>
                                        <span class="text-[11px] sm:text-xs">
                                            Rp
                                            {{ number_format($order->total_price - $order->shipping_cost, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] sm:text-xs text-gray-600">Shipping Cost
                                            ({{ $order->courier }})
                                        </span>
                                        <span class="text-[11px] sm:text-xs">
                                            Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between pt-1 border-t border-gray-200">
                                        <span class="text-xs sm:text-sm font-medium text-gray-900">Total</span>
                                        <span class="text-xs sm:text-sm font-medium text-gray-900">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="border-t border-gray-100 px-3 py-2 sm:px-4 sm:py-3">
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0">
                                    <div
                                        class="text-[11px] sm:text-xs text-gray-500 truncate max-w-full sm:max-w-[50%]">
                                        <span class="font-medium">Address: </span>{{ $order->shipping_address }}
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if ($order->status === 'accepted')
                                            @php
                                                $hasReviewed = \App\Models\Review::where('order_id', $order->id)
                                                    ->where('user_id', auth()->id())
                                                    ->exists();
                                            @endphp

                                            @if ($hasReviewed)
                                                <a href="{{ route('reviews.show', ['order' => $order->id]) }}"
                                                    class="inline-flex items-center px-3 py-1.5 sm:px-2 sm:py-1 border border-gray-300 rounded text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                    View Review
                                                </a>
                                            @else
                                                <a href="{{ route('reviews.create', ['order_id' => $order->id]) }}"
                                                    class="inline-flex items-center px-3 py-1.5 sm:px-2 sm:py-1 bg-[#FF9C08] border border-transparent rounded text-xs font-medium text-white hover:bg-[#e68a00]">
                                                    Review
                                                </a>
                                            @endif
                                        @endif

                                        <a href="{{ route('orders.details', $order->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 sm:px-2 sm:py-1 bg-[#FF9C08] border border-transparent rounded text-xs font-medium text-white hover:bg-[#e68a00]">
                                            Details
                                        </a>
                                    </div>
                                </div>
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
</x-app-layout>
