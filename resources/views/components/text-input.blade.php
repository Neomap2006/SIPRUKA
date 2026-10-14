@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-black/80 border border-[#004B87] text-slate-100 placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#00D1FF]/50 focus:border-[#00E9FF] transition shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)] focus:shadow-[0_0_12px_rgba(0,209,255,0.3)]']) }}>

