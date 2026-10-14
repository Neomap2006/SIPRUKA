<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center gap-2 px-5 py-2.5 bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-500/40 rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-lg shadow-red-600/10 active:scale-95 focus:outline-none focus:ring-2 focus:ring-red-500']) }}>
    {{ $slot }}
</button>
