<x-app-layout>
    @section('title', 'Product Management') <!-- Title halaman Produk -->

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('products.manage.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-gray-700 font-semibold mb-1">
                            Product Name
                        </label>
                        <input type="text" id="name" name="name"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <!-- Product Category -->
                    <div>
                        <label for="category_id" class="block text-gray-700 font-semibold mb-1">
                            Category
                        </label>
                        <select id="category_id" name="category_id"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Description -->
                    <div>
                        <label for="description" class="block text-gray-700 font-semibold mb-1">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"></textarea>
                    </div>

                    <!-- Product Price -->
                    <div>
                        <label for="price" class="block text-gray-700 font-semibold mb-1">
                            Price (Rp)
                        </label>
                        <input type="number" id="price" name="price"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="stock" class="block text-gray-700 font-semibold mb-1">
                            Stock
                        </label>
                        <input type="number" id="stock" name="stock"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <!-- Product Weight -->
                    <div>
                        <label for="weight" class="block text-gray-700 font-semibold mb-1">
                            Weight (grams)
                        </label>
                        <input type="number" id="weight" name="weight"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <!-- Product Images -->
                    <div>
                        <label for="images" class="block text-gray-700 font-semibold mb-1">
                            Product Images
                        </label>
                        <input type="file" id="images" name="images[]" multiple
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
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
