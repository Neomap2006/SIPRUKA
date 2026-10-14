<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no" class="notranslate">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" content="notranslate">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $pageTitle = config('app.name', 'SIPRUKA');
            if (request()->routeIs('dashboard')) {
                $pageTitle = 'Dashboard';
            } elseif (request()->routeIs('ruangan.*')) {
                $pageTitle = 'Ruangan';
            } elseif (request()->routeIs('peminjaman-ruangan.*')) {
                $pageTitle = 'Peminjaman Ruangan';
            } elseif (request()->routeIs('users.*')) {
                $pageTitle = 'Manajemen User';
            } elseif (request()->routeIs('whatsapp-setting.*')) {
                $pageTitle = 'WhatsApp Setting';
            }
        @endphp
        <title>{{ $pageTitle }}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/Logo untuk Pov icon.png') }}">
        <link rel="shortcut icon" href="{{ asset('assets/Logo untuk Pov icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Prevent Alpine.js FOUC / Modal Flash -->
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-100 bg-black overflow-hidden selection:bg-cyan-500 selection:text-black" x-data="{ mobileOpen: false, desktopOpen: true }">
        <div class="flex h-screen overflow-hidden bg-black">
            
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative z-0 bg-black">
                
                <!-- Mobile Topbar -->
                <header class="bg-[#050c18] border-b border-[#004B87]/60 lg:hidden flex items-center justify-between px-4 h-16 shrink-0 z-30 shadow-lg">
                    <div class="flex items-center gap-3">
                        <button @click="mobileOpen = true" class="p-2 -mr-2 text-slate-400 hover:text-[#00D1FF] focus:outline-none rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        <span class="font-bold text-lg tracking-wider text-white">SIPRUKA LP3I</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-notification-bell />
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#004B87] to-[#00D1FF] flex items-center justify-center text-white font-bold text-sm shadow-[0_0_10px_rgba(0,209,255,0.4)]">
                            {{ substr(Auth::user()->nama, 0, 1) }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="p-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 transition-colors border border-red-500/20" title="Keluar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Desktop Page Header -->
                <header class="bg-[#050c18] border-b border-[#004B87]/50 shadow-md hidden lg:flex items-center justify-between shrink-0 z-30 px-5 py-3.5 gap-4">
                    <div class="flex items-center gap-4 flex-1">
                        <button @click="desktopOpen = !desktopOpen" class="text-slate-400 hover:text-[#00D1FF] hover:bg-[#004B87]/20 focus:outline-none p-2 rounded-lg transition-colors border border-transparent hover:border-[#004B87]/40 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        @isset($header)
                            <div class="flex-1 min-w-0">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    
                    <!-- User Profile Top Right -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <x-notification-bell />
                        <div class="flex items-center gap-3 px-3 py-1.5 rounded-xl bg-black/50 border border-[#004B87]/40">
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-100">{{ Auth::user()->nama }}</p>
                                <p class="text-xs text-[#00E9FF] font-semibold capitalize flex items-center justify-end gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#00D1FF] shadow-[0_0_6px_#00D1FF]"></span>
                                    {{ Auth::user()->role }}
                                </p>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-[#004B87] to-[#00D1FF] flex items-center justify-center text-white font-bold text-base shrink-0 shadow-[0_0_12px_rgba(0,209,255,0.4)] border border-[#00D1FF]/40">
                                {{ substr(Auth::user()->nama, 0, 1) }}
                            </div>
                        </div>
                        <div class="w-px h-8 bg-[#004B87]/50 mx-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="flex justify-center items-center gap-2 p-2 px-3.5 rounded-lg bg-red-500/10 hover:bg-red-500/25 text-sm font-semibold text-red-400 transition-colors border border-red-500/30 shadow-[0_0_10px_rgba(239,68,68,0.15)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </header>
                
                <!-- Mobile Page Header -->
                @isset($header)
                    <div class="bg-[#050c18] border-b border-[#004B87]/50 shadow-sm lg:hidden shrink-0 z-10">
                        <div class="py-3 px-4">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- Main Scrollable Content -->
                <main class="flex-1 overflow-y-auto overflow-x-hidden bg-black scrollbar-thin pb-20 sm:pb-12">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Universal Interactive Datatable Script (Entries per page, Search, Sorting & Pagination) -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function initUniversalDatatables() {
                    const tables = document.querySelectorAll('table:not([data-no-datatable]):not(.no-datatable)');

                    tables.forEach((table) => {
                        const thead = table.querySelector('thead');
                        const tbody = table.querySelector('tbody');
                        if (!thead || !tbody) return;

                        if (table.getAttribute('data-datatable-initialized') === 'true') return;
                        table.setAttribute('data-datatable-initialized', 'true');

                        let allRows = Array.from(tbody.querySelectorAll('tr'));
                        if (allRows.length === 0) return;

                        let pageSize = 10;
                        let currentPage = 1;
                        let searchQuery = '';
                        let currentSortCol = null;
                        let currentSortDir = 'none';

                        // Create Top Control Bar (Styled as Card Header)
                        const topBar = document.createElement('div');
                        topBar.className = 'datatable-top px-3 sm:px-6 py-3 border-b border-[#004B87]/60 bg-[#050c18] flex flex-row items-center justify-between gap-2 text-xs font-semibold text-slate-300 w-full';
                        
                        topBar.innerHTML = `
                            <div class="flex items-center gap-1.5 sm:gap-2.5 text-[11px] sm:text-xs shrink-0">
                                <span class="text-slate-400">Tampilkan</span>
                                <select class="dt-entries-select bg-[#004B87]/30 border border-[#004B87] text-[#00E9FF] font-bold rounded-xl px-2 py-1 sm:px-3 sm:py-1.5 focus:ring-1 focus:ring-[#00D1FF] focus:border-[#00E9FF] outline-none cursor-pointer transition-all hover:bg-[#004B87]/50 text-xs">
                                    <option value="10" class="bg-[#050c18] text-white" selected>10</option>
                                    <option value="15" class="bg-[#050c18] text-white">15</option>
                                    <option value="25" class="bg-[#050c18] text-white">25</option>
                                    <option value="50" class="bg-[#050c18] text-white">50</option>
                                    <option value="-1" class="bg-[#050c18] text-white">Semua</option>
                                </select>
                                <span class="text-slate-400">data <span class="hidden sm:inline">per halaman</span></span>
                            </div>
                            <div class="relative w-36 xs:w-48 sm:w-72 shrink-0">
                                <input type="text" placeholder="Cari data..." class="dt-search-input w-full bg-[#004B87]/20 border border-[#004B87]/80 text-slate-100 placeholder-slate-500 rounded-xl pl-8 sm:pl-9 pr-3 sm:pr-4 py-1.5 sm:py-2 text-xs focus:ring-1 focus:ring-[#00D1FF] focus:border-[#00E9FF] focus:bg-[#004B87]/30 outline-none transition-all" />
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 absolute left-2.5 sm:left-3 top-1/2 -translate-y-1/2 text-[#00D1FF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        `;

                        // Create Bottom Control Bar (Styled as Card Footer)
                        const bottomBar = document.createElement('div');
                        bottomBar.className = 'datatable-bottom px-3 sm:px-6 py-3 border-t border-[#004B87]/60 bg-[#050c18] flex flex-row items-center justify-between gap-2 text-xs text-slate-300 font-medium w-full';
                        bottomBar.innerHTML = `
                            <div class="dt-info text-slate-300 flex items-center justify-start gap-1 text-left text-[11px] sm:text-xs font-semibold shrink-0"></div>
                            <div class="dt-pagination flex items-center justify-end gap-1 sm:gap-1.5 shrink-0 ml-auto"></div>
                        `;

                        // Insert seamlessly inside card wrapper
                        const tableParent = table.parentElement;
                        const cardContainer = table.closest('.rounded-2xl, .rounded-xl, .bg-cyber-card, .border') || tableParent;

                        if (cardContainer) {
                            const overflowWrapper = table.closest('.overflow-x-auto');
                            if (overflowWrapper && overflowWrapper !== cardContainer) {
                                cardContainer.insertBefore(topBar, overflowWrapper);
                                cardContainer.appendChild(bottomBar);
                            } else {
                                cardContainer.insertBefore(topBar, table);
                                cardContainer.appendChild(bottomBar);
                            }
                        } else {
                            table.parentNode.insertBefore(topBar, table);
                            table.parentNode.appendChild(bottomBar);
                        }

                        const entriesSelect = topBar.querySelector('.dt-entries-select');
                        const searchInput = topBar.querySelector('.dt-search-input');
                        const infoDiv = bottomBar.querySelector('.dt-info');
                        const paginationDiv = bottomBar.querySelector('.dt-pagination');

                        // Headers setup for sorting
                        const headers = thead.querySelectorAll('th');
                        headers.forEach((th, colIdx) => {
                            const text = th.innerText.trim().toLowerCase();
                            if (text === 'aksi' || text === 'action') return;

                            th.classList.add('cursor-pointer', 'select-none', 'hover:text-[#00E9FF]', 'transition-colors');
                            th.setAttribute('title', 'Klik untuk mengurutkan');

                            if (!th.querySelector('.sort-icon')) {
                                const iconSpan = document.createElement('span');
                                iconSpan.className = 'sort-icon ml-1.5 inline-block text-[10px] opacity-60 text-[#00D1FF]';
                                iconSpan.innerHTML = '↕';
                                th.appendChild(iconSpan);
                            }

                            th.onclick = function () {
                                if (currentSortCol === colIdx) {
                                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                                } else {
                                    currentSortCol = colIdx;
                                    currentSortDir = 'asc';
                                }

                                headers.forEach(otherTh => {
                                    const icon = otherTh.querySelector('.sort-icon');
                                    if (icon) {
                                        icon.innerHTML = '↕';
                                        icon.classList.remove('opacity-100', 'text-white');
                                        icon.classList.add('opacity-60', 'text-[#00D1FF]');
                                    }
                                });

                                const icon = th.querySelector('.sort-icon');
                                if (icon) {
                                    icon.innerHTML = currentSortDir === 'asc' ? '▲' : '▼';
                                    icon.classList.remove('opacity-60');
                                    icon.classList.add('opacity-100', 'text-white');
                                }

                                render();
                            };
                        });

                        function getFilteredAndSortedRows() {
                            let filtered = allRows.filter(row => {
                                if (!searchQuery) return true;
                                const text = row.innerText.toLowerCase();
                                return text.includes(searchQuery.toLowerCase());
                            });

                            if (currentSortCol !== null && currentSortDir !== 'none') {
                                filtered.sort((a, b) => {
                                    const cellA = a.children[currentSortCol] ? a.children[currentSortCol].innerText.trim() : '';
                                    const cellB = b.children[currentSortCol] ? b.children[currentSortCol].innerText.trim() : '';

                                    const numA = parseFloat(cellA.replace(/[^0-9.-]+/g, ''));
                                    const numB = parseFloat(cellB.replace(/[^0-9.-]+/g, ''));
                                    const isNum = !isNaN(numA) && !isNaN(numB) && cellA.match(/^\d+/) && cellB.match(/^\d+/);

                                    let cmp = 0;
                                    if (isNum) {
                                        cmp = numA - numB;
                                    } else {
                                        cmp = cellA.localeCompare(cellB, undefined, { numeric: true, sensitivity: 'base' });
                                    }

                                    return currentSortDir === 'asc' ? cmp : -cmp;
                                });
                            }

                            return filtered;
                        }

                        function render() {
                            const filteredRows = getFilteredAndSortedRows();
                            const totalRows = filteredRows.length;
                            const effectivePageSize = pageSize === -1 ? totalRows : pageSize;
                            const totalPages = effectivePageSize > 0 ? Math.ceil(totalRows / effectivePageSize) : 1;

                            if (currentPage > totalPages) currentPage = totalPages || 1;
                            if (currentPage < 1) currentPage = 1;

                            const startIdx = (currentPage - 1) * effectivePageSize;
                            const endIdx = pageSize === -1 ? totalRows : Math.min(startIdx + effectivePageSize, totalRows);

                            tbody.innerHTML = '';

                            if (totalRows === 0) {
                                const colCount = headers.length || 1;
                                tbody.innerHTML = `<tr><td colspan="${colCount}" class="py-8 text-center text-slate-400 italic font-medium">Tidak ada data yang cocok ditemukan.</td></tr>`;
                                infoDiv.innerHTML = 'Menampilkan 0 data';
                                paginationDiv.innerHTML = '';
                                return;
                            }

                            const pageRows = filteredRows.slice(startIdx, endIdx);
                            pageRows.forEach(row => tbody.appendChild(row));

                            const showStart = totalRows > 0 ? startIdx + 1 : 0;
                            infoDiv.innerHTML = `Menampilkan <span class="font-bold text-[#00E9FF] mx-0.5">${showStart}</span> sampai <span class="font-bold text-[#00E9FF] mx-0.5">${endIdx}</span> dari <span class="font-bold text-[#00E9FF] mx-0.5">${totalRows}</span> data`;

                            paginationDiv.innerHTML = '';
                            if (totalPages <= 1) return;

                            const prevBtn = document.createElement('button');
                            prevBtn.className = `px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl border text-[11px] sm:text-xs font-semibold transition-all ${currentPage === 1 ? 'border-slate-800 text-slate-600 cursor-not-allowed' : 'border-[#004B87]/60 text-slate-200 hover:bg-[#004B87]/40 hover:text-[#00E9FF]'}`;
                            prevBtn.innerHTML = '&laquo; Prev';
                            prevBtn.disabled = currentPage === 1;
                            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; render(); } };
                            paginationDiv.appendChild(prevBtn);

                            let startPage = Math.max(1, currentPage - 2);
                            let endPage = Math.min(totalPages, currentPage + 2);

                            if (startPage > 1) {
                                paginationDiv.appendChild(createPageBtn(1));
                                if (startPage > 2) {
                                    const ellipsis = document.createElement('span');
                                    ellipsis.className = 'px-1 text-slate-500 text-[10px] sm:text-xs';
                                    ellipsis.innerText = '...';
                                    paginationDiv.appendChild(ellipsis);
                                }
                            }

                            for (let i = startPage; i <= endPage; i++) {
                                paginationDiv.appendChild(createPageBtn(i));
                            }

                            if (endPage < totalPages) {
                                if (endPage < totalPages - 1) {
                                    const ellipsis = document.createElement('span');
                                    ellipsis.className = 'px-1 text-slate-500 text-[10px] sm:text-xs';
                                    ellipsis.innerText = '...';
                                    paginationDiv.appendChild(ellipsis);
                                }
                                paginationDiv.appendChild(createPageBtn(totalPages));
                            }

                            const nextBtn = document.createElement('button');
                            nextBtn.className = `px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl border text-[11px] sm:text-xs font-semibold transition-all ${currentPage === totalPages ? 'border-slate-800 text-slate-600 cursor-not-allowed' : 'border-[#004B87]/60 text-slate-200 hover:bg-[#004B87]/40 hover:text-[#00E9FF]'}`;
                            nextBtn.innerHTML = 'Next &raquo;';
                            nextBtn.disabled = currentPage === totalPages;
                            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; render(); } };
                            paginationDiv.appendChild(nextBtn);
                        }

                        function createPageBtn(p) {
                            const btn = document.createElement('button');
                            const isActive = p === currentPage;
                            btn.className = `px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl text-[11px] sm:text-xs font-bold transition-all ${isActive ? 'bg-gradient-to-r from-[#004B87] to-[#00D1FF] text-white shadow-[0_0_12px_rgba(0,209,255,0.4)] border border-[#00D1FF]' : 'border border-[#004B87]/40 text-slate-300 hover:bg-[#004B87]/30 hover:text-[#00E9FF]'}`;
                            btn.innerText = p;
                            btn.onclick = () => {
                                currentPage = p;
                                render();
                            };
                            return btn;
                        }

                        entriesSelect.addEventListener('change', function () {
                            pageSize = parseInt(this.value);
                            currentPage = 1;
                            render();
                        });

                        searchInput.addEventListener('input', function () {
                            searchQuery = this.value.trim();
                            currentPage = 1;
                            render();
                        });

                        render();
                    });
                }

                initUniversalDatatables();
            });
        </script>
    </body>
</html>

