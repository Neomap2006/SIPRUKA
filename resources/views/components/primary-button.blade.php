<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#004B87] via-[#0096B8] to-[#00D1FF] hover:from-[#005FA8] hover:to-[#00E9FF] border border-[#00D1FF]/50 rounded-xl font-bold text-sm text-white transition duration-200 ease-in-out shadow-[0_0_15px_rgba(0,209,255,0.35)] hover:shadow-[0_0_25px_rgba(0,209,255,0.65)] focus:outline-none focus:ring-2 focus:ring-[#00E9FF] active:scale-[0.98]']) }}>
    {{ $slot }}
</button>

