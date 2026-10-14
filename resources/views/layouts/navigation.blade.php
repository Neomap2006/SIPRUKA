<!-- Sidebar Overlay for Mobile -->
<div x-show="mobileOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0" 
     class="fixed inset-0 bg-black/80 backdrop-blur-md z-40 lg:hidden"
     @click="mobileOpen = false"></div>

<!-- Sidebar Layout -->
<aside :class="{
        'translate-x-0': mobileOpen, 
        '-translate-x-full': !mobileOpen,
        'lg:translate-x-0 lg:w-64 lg:static': desktopOpen,
        'lg:-translate-x-full lg:w-0 lg:hidden': !desktopOpen
       }" 
       class="fixed inset-y-0 left-0 z-50 w-72 bg-black border-r border-[#004B87]/60 transition-all duration-300 ease-in-out flex flex-col shadow-[4px_0_24px_rgba(0,209,255,0.08)] lg:shadow-none">
    
    <!-- Sidebar Header (Logo) -->
    <div class="py-6 px-4 shrink-0 flex flex-col items-center justify-center border-b border-[#004B87]/50 bg-gradient-to-b from-[#050c18] via-black to-black relative">
        <button @click="mobileOpen = false" class="lg:hidden absolute top-3 right-3 p-1.5 text-slate-400 hover:text-[#00D1FF] focus:outline-none rounded-md">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-center group w-full">
            <div class="p-2 rounded-2xl bg-[#004B87]/10 border border-[#00D1FF]/20 group-hover:border-[#00D1FF]/50 transition-all duration-300 shadow-[0_0_20px_rgba(0,209,255,0.15)] group-hover:shadow-[0_0_30px_rgba(0,209,255,0.3)] mb-3 animate-float">
                <x-application-logo class="w-36 h-36 shrink-0" />
            </div>
            <span class="text-xl font-black text-white tracking-wider group-hover:text-[#00E9FF] transition-colors leading-tight">SIPRUKA LP3I</span>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight group-hover:text-slate-300 transition-colors leading-snug mt-1">Sistem Peminjaman Ruangan Kampus</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 scrollbar-thin">
        <div class="text-[10px] font-bold text-[#00D1FF]/70 uppercase tracking-widest mb-4 px-3 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-[#00D1FF] shadow-[0_0_6px_#00D1FF]"></span>
            Main Menu
        </div>
        
        <a href="{{ route('dashboard') }}" 
           @click="mobileOpen = false"
           class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#004B87]/30 text-[#00E9FF] font-semibold border border-[#00D1FF]/60 shadow-[0_0_15px_rgba(0,209,255,0.25)]' : 'text-slate-400 hover:text-[#00E9FF] hover:bg-[#050c18] hover:border hover:border-[#004B87]/40' }}">
            <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-[#00D1FF] filter drop-shadow-[0_0_6px_#00D1FF]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            {{ __('Dashboard') }}
        </a>

        <a href="{{ route('ruangan.index') }}" 
           @click="mobileOpen = false"
           class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('ruangan.*') ? 'bg-[#004B87]/30 text-[#00E9FF] font-semibold border border-[#00D1FF]/60 shadow-[0_0_15px_rgba(0,209,255,0.25)]' : 'text-slate-400 hover:text-[#00E9FF] hover:bg-[#050c18] hover:border hover:border-[#004B87]/40' }}">
            <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('ruangan.*') ? 'text-[#00D1FF] filter drop-shadow-[0_0_6px_#00D1FF]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            {{ __('Data Ruangan') }}
        </a>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}" 
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-[#004B87]/30 text-[#00E9FF] font-semibold border border-[#00D1FF]/60 shadow-[0_0_15px_rgba(0,209,255,0.25)]' : 'text-slate-400 hover:text-[#00E9FF] hover:bg-[#050c18] hover:border hover:border-[#004B87]/40' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('users.*') ? 'text-[#00D1FF] filter drop-shadow-[0_0_6px_#00D1FF]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{ __('Manajemen User') }}
            </a>

            <a href="{{ route('whatsapp-setting.index') }}" 
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('whatsapp-setting.*') ? 'bg-[#004B87]/30 text-[#00E9FF] font-semibold border border-[#00D1FF]/60 shadow-[0_0_15px_rgba(0,209,255,0.25)]' : 'text-slate-400 hover:text-[#00E9FF] hover:bg-[#050c18] hover:border hover:border-[#004B87]/40' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('whatsapp-setting.*') ? 'text-[#00D1FF] filter drop-shadow-[0_0_6px_#00D1FF]' : 'text-emerald-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                </svg>
                {{ __('WhatsApp Setting') }}
            </a>
        @endif

        <div class="text-[10px] font-bold text-[#00D1FF]/70 uppercase tracking-widest mt-8 mb-4 px-3 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-[#004B87] shadow-[0_0_6px_#004B87]"></span>
            Transaksi
        </div>

        <a href="{{ route('peminjaman-ruangan.index') }}" 
           @click="mobileOpen = false"
           class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('peminjaman-ruangan.index', 'peminjaman-ruangan.create', 'peminjaman-ruangan.edit', 'peminjaman-ruangan.show') ? 'bg-[#004B87]/30 text-[#00E9FF] font-semibold border border-[#00D1FF]/60 shadow-[0_0_15px_rgba(0,209,255,0.25)]' : 'text-slate-400 hover:text-[#00E9FF] hover:bg-[#050c18] hover:border hover:border-[#004B87]/40' }}">
            <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('peminjaman-ruangan.index', 'peminjaman-ruangan.create', 'peminjaman-ruangan.edit', 'peminjaman-ruangan.show') ? 'text-[#00D1FF] filter drop-shadow-[0_0_6px_#00D1FF]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ __('Peminjaman Ruangan') }}
        </a>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('peminjaman-ruangan.laporan') }}" 
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('peminjaman-ruangan.laporan') ? 'bg-[#004B87]/30 text-[#00E9FF] font-semibold border border-[#00D1FF]/60 shadow-[0_0_15px_rgba(0,209,255,0.25)]' : 'text-slate-400 hover:text-[#00E9FF] hover:bg-[#050c18] hover:border hover:border-[#004B87]/40' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('peminjaman-ruangan.laporan') ? 'text-[#00D1FF] filter drop-shadow-[0_0_6px_#00D1FF]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                {{ __('Laporan & Rekapitulasi') }}
            </a>
        @endif
    </div>
</aside>

