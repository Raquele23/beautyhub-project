<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex appearance-none bg-violet-400 items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-500 focus:bg-violet-500 active:bg-violet-500 focus:outline-none focus:ring-2 focus:ring-[#A675D6] focus:ring-offset-2 disabled:bg-violet-300 disabled:cursor-not-allowed transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
