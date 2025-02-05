@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>
    @section('title', 'Edit Product')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                {{ __('Edit Product') }}
            </h2>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('products.manage.update', $manage) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-gray-700 font-semibold mb-1">
                            Product Name
                        </label>
                        <input type="text" id="name" name="name" value="{{ $manage->name }}"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <!-- Product Category -->
                    <div>
                        <label for="category_id" class="block text-gray-700 font-semibold mb-1">
                            Product Category
                        </label>
                        <select id="category_id" name="category_id"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Select Product Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $manage->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Description -->
                    <div>
                        <label for="description" class="block text-gray-700 font-semibold mb-1">
                            Product Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">{{ $manage->description }}</textarea>
                    </div>

                    <!-- Product Price -->
                    <div>
                        <label for="price" class="block text-gray-700 font-semibold mb-1">
                            Product Price (IDR)
                        </label>
                        <input type="number" id="price" name="price" value="{{ $manage->price }}"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <div>
                        <label for="stock" class="block text-gray-700 font-semibold mb-1">
                            Product Stock
                        </label>
                        <input type="number" id="stock" name="stock" value="{{ $manage->stock }}"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <!-- Product Weight -->
                    <div>
                        <label for="weight" class="block text-gray-700 font-semibold mb-1">
                            Product Weight (gram)
                        </label>
                        <input type="number" id="weight" name="weight" value="{{ $manage->weight }}"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <!-- Product Images -->
                    <div>
                        <label for="images" class="block text-gray-700 font-semibold mb-1">
                            Product Images
                        </label>
                        <input type="file" id="images" name="images[]" multiple
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500";
                            <!-- Old Images List -->
                        @if ($manage->images->isNotEmpty())
                            <div class="mt-3 space-y-2">
                                @foreach ($manage->images as $image)
                                    <div class="flex items-center">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="Product Image"
                                            class="w-20 h-20 object-cover rounded mr-4">
                                        @if ($manage->images->count() > 1)
                                            <label class="flex items-center text-sm text-gray-700">
                                                <input type="checkbox" name="delete_images[]"
                                                    value="{{ $image->id }}" class="mr-2 focus:ring-blue-500">
                                                Delete This Image
                                            </label>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Save Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="bg-[#FF9C08] text-white px-4 py-2 rounded shadow hover:bg-[#E68A00] 
                                   transition-colors duration-200 focus:outline-none">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
