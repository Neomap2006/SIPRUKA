<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-black/60 hover:bg-[#050c18] border border-[#004B87] hover:border-[#00D1FF] rounded-xl font-semibold text-sm text-slate-300 hover:text-[#00E9FF] transition duration-200 ease-in-out shadow-sm focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50']) }}>
    {{ $slot }}
</button>
