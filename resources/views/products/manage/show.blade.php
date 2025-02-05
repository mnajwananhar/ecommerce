@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>

    @section('title', 'Product details') <!-- Title halaman Produk -->

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Product Details
            </h2>
            <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-[#FF9C08] flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Product Images -->
                        <div>
                            <div class="mb-4">
                                <img src="{{ $product->images->first() ? Storage::url($product->images->first()->image_path) : asset('images/placeholder.png') }}"
                                    class="w-full aspect-square object-cover rounded-lg shadow-lg">
                            </div>
                            @if ($product->images->count() > 1)
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach ($product->images->skip(1) as $image)
                                        <img src="{{ Storage::url($image->image_path) }}"
                                            class="w-full aspect-square object-cover rounded-lg shadow-sm cursor-pointer hover:opacity-75 transition">
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                            <div class="flex items-center gap-4 mb-6">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    {{ $product->category->name }}
                                </span>
                                <span
                                    class="px-3 py-1 {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} rounded-full text-sm">
                                    {{ $product->status }}
                                </span>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Price</h3>
                                    <p class="text-3xl font-bold text-blue-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Stock</h3>
                                    <p class="text-gray-700">{{ $product->stock }} units</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Weight</h3>
                                    <p class="text-gray-700">{{ $product->weight }} grams</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Description</h3>
                                    <div class="prose prose-blue max-w-none mt-2">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                </div>
                            </div>

                            @if (Auth::user()->role === 'seller' && $product->seller_id === Auth::id())
                                <div class="mt-8 flex gap-4">
                                    <a href="{{ route('products.manage.edit', $product) }}"
                                        class="flex-1 bg-[#FF9C08] hover:bg-[#E68A00] text-white py-2 px-4 rounded-lg flex items-center justify-center transition duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Product
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
