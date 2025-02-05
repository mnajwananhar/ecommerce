<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#FF9C08] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#e68a00] focus:bg-[#e68a00] active:bg-[#cc7a00] focus:outline-none focus:ring-2 focus:ring-[#FF9C08] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
