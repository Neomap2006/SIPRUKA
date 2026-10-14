<div x-data="{
    open: false,
    loading: false,
    unreadCount: 0,
    notifications: [],
    init() {
        this.fetchNotifications();
        setInterval(() => this.fetchNotifications(), 15000);
    },
    async fetchNotifications() {
        try {
            const res = await fetch('{{ route('notifications.fetch') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (res.ok) {
                const data = await res.json();
                this.unreadCount = data.unread_count;
                this.notifications = data.notifications;
            } else if (res.status === 401 || res.status === 419) {
                // Session ended or unauthenticated
            }
        } catch (e) {
            console.error('Failed to fetch notifications', e);
        }
    },
    async markRead(item) {
        if (!item.is_read) {
            try {
                await fetch(`/notifications/${item.id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                item.is_read = true;
                if (this.unreadCount > 0) this.unreadCount--;
            } catch (e) {
                console.error(e);
            }
        }
        if (item.link) {
            try {
                const url = new URL(item.link, window.location.origin);
                window.location.href = url.pathname + url.search + url.hash;
            } catch (e) {
                window.location.href = item.link;
            }
        }
    },
    async markAllRead() {
        try {
            await fetch('{{ route('notifications.read-all') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            this.unreadCount = 0;
            this.notifications.forEach(n => n.is_read = true);
        } catch (e) {
            console.error(e);
        }
    }
}" class="relative">
    
    <!-- Bell Button -->
    <button @click="open = !open" 
            type="button" 
            class="relative p-2.5 rounded-xl text-slate-200 hover:text-[#00E9FF] hover:bg-[#004B87]/40 border border-transparent hover:border-[#00D1FF]/50 focus:outline-none transition-all duration-200">
        
        <!-- Bell Icon -->
        <svg class="w-6 h-6 transition-transform duration-200 hover:scale-110" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>

        <!-- Unread Pulsing Badge -->
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-80"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-gradient-to-r from-red-600 to-rose-600 text-[10px] font-black text-white items-center justify-center shadow-[0_0_10px_rgba(239,68,68,0.9)]" 
                      x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
            </span>
        </template>
    </button>

    <!-- Dark Backdrop Overlay when Open -->
    <div x-show="open" 
         @click="open = false" 
         x-cloak 
         x-transition:enter="transition opacity ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition opacity ease-in duration-150" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black/60 backdrop-blur-xs z-[9990]"></div>

    <!-- Notification Dropdown Menu (Solid Cyber High-Contrast Card) -->
    <div x-show="open" 
         @click.away="open = false" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2" 
         x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
         x-transition:leave-end="opacity-0 scale-95 -translate-y-2" 
         class="fixed left-3 right-3 top-16 max-w-md mx-auto sm:max-w-none sm:absolute sm:left-auto sm:right-0 sm:top-full sm:mt-2 sm:w-[430px] rounded-2xl bg-[#0a1628] border-2 border-[#00E9FF] shadow-[0_20px_60px_rgba(0,0,0,0.95),0_0_30px_rgba(0,209,255,0.4)] z-[9999] overflow-hidden">
        
        <!-- Header -->
        <div class="px-5 py-3.5 border-b border-[#00D1FF]/40 bg-[#050c18] flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-[#00E9FF] shadow-[0_0_10px_#00E9FF] animate-pulse"></span>
                <h4 class="text-xs font-black tracking-wider text-white uppercase">Notifikasi Sistem</h4>
                <span x-show="unreadCount > 0" 
                      class="px-2.5 py-0.5 rounded-full bg-red-600 text-white font-black text-[10px] uppercase shadow-[0_0_8px_rgba(239,68,68,0.6)]" 
                      x-text="unreadCount + ' Baru'"></span>
            </div>
            <div class="flex items-center gap-3">
                <button x-show="unreadCount > 0" 
                        @click="markAllRead()" 
                        class="text-xs font-extrabold text-[#00E9FF] hover:text-white transition-all underline decoration-solid">
                    Tandai Semua Dibaca
                </button>
                <button @click="open = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors" title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Body / Notifications List -->
        <div class="max-h-[390px] overflow-y-auto divide-y divide-[#004B87]/50 scrollbar-thin bg-[#06101e]">
            <template x-if="notifications.length === 0">
                <div class="p-10 text-center text-slate-300 text-xs font-medium space-y-2">
                    <div class="w-12 h-12 rounded-full bg-[#004B87]/30 border border-[#00D1FF]/40 flex items-center justify-center mx-auto text-[#00E9FF]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <p class="text-white font-extrabold text-sm">Belum ada notifikasi baru</p>
                    <p class="text-xs text-slate-400">Semua aktivitas sistem akan ditampilkan di sini</p>
                </div>
            </template>

            <template x-for="item in notifications" :key="item.id">
                <div @click="markRead(item)" 
                     class="p-4 transition-all duration-200 cursor-pointer flex items-start gap-3.5 group"
                     :class="item.is_read ? 'bg-[#06101e] border-l-4 border-l-slate-600 hover:bg-[#004B87]/30' : 'bg-[#0d213a] border-l-4 border-l-[#00E9FF] hover:bg-[#004B87]/50 shadow-md'">
                    
                    <!-- Solid High-Contrast Icon Badges -->
                    <div class="mt-0.5 shrink-0">
                        <template x-if="item.type === 'disetujui'">
                            <span class="w-8 h-8 rounded-xl bg-emerald-500 text-black font-black flex items-center justify-center text-sm shadow-[0_0_12px_rgba(16,185,129,0.6)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                        </template>
                        <template x-if="item.type === 'ditolak'">
                            <span class="w-8 h-8 rounded-xl bg-red-600 text-white font-black flex items-center justify-center text-sm shadow-[0_0_12px_rgba(239,68,68,0.7)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </span>
                        </template>
                        <template x-if="item.type === 'pengajuan_baru'">
                            <span class="w-8 h-8 rounded-xl bg-[#00E9FF] text-black font-black flex items-center justify-center text-sm shadow-[0_0_12px_rgba(0,233,255,0.7)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                        </template>
                        <template x-if="!['disetujui', 'ditolak', 'pengajuan_baru'].includes(item.type)">
                            <span class="w-8 h-8 rounded-xl bg-cyan-500 text-black font-black flex items-center justify-center text-sm shadow-[0_0_12px_rgba(6,182,212,0.6)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </span>
                        </template>
                    </div>

                    <!-- Details with High-Contrast Text -->
                    <div class="flex-1 min-w-0 space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <h5 class="text-xs font-extrabold text-white group-hover:text-[#00E9FF] transition-colors truncate" x-text="item.title"></h5>
                            <span class="text-[10px] font-extrabold text-[#00E9FF] bg-[#004B87] px-2 py-0.5 rounded-md shrink-0 border border-[#00D1FF]/40" x-text="item.time_ago"></span>
                        </div>
                        <p class="text-xs text-slate-100 font-medium leading-relaxed break-words" x-text="item.message"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 bg-[#050c18] border-t border-[#00D1FF]/40 text-center">
            <a href="{{ route('peminjaman-ruangan.index') }}" 
               class="text-xs font-black text-[#00E9FF] hover:text-white transition-all flex items-center justify-center gap-1.5 hover:gap-2">
                <span>Lihat Semua Peminjaman Ruangan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>
</div>
