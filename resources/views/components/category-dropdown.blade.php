<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
        class="text-gray-600 hover:text-[#FF9C08] flex items-center space-x-2 px-3 py-2 rounded-lg" aria-haspopup="true"
        aria-expanded="open">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        <span>Category</span>
    </button>

    <div x-show="open" @click.away="open = false"
        class="absolute left-0 z-50 mt-2 w-52 bg-white rounded-lg shadow-lg border border-gray-100">
        <div class="py-2 max-h-[70vh] overflow-y-auto">
            @foreach ($categories->sortBy('name') as $category)
                <a href="{{ route('search', ['category' => $category->id]) }}"
                    class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-[#FFF5E6] hover:text-[#FF9C08]">
                    {{ $category->name }}
                    <span class="text-xs text-gray-400">{{ $category->products_count }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
