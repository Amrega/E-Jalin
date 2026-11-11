<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Referensi Ruas - E-Jalin Jawa Timur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            DEFAULT: '#0f2a71',
                            700: '#153894',
                            800: '#112d7a',
                            900: '#0f2a71',
                            950: '#09183f',
                        },
                        gold: {
                            DEFAULT: '#ffb700',
                            400: '#ffc42e',
                            500: '#ffb700',
                            600: '#e69d00',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans flex flex-col min-h-screen selection:bg-[#ffb700] selection:text-[#0f2a71]">

    <!-- Top Main Header (SiTIA / E-Jalin Style) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                
                <!-- Left: Sidebar Toggle & Brand -->
                <div class="flex items-center gap-3">
                    <button id="toggleSidebarBtn" class="p-2 rounded-lg text-slate-500 hover:text-[#0f2a71] hover:bg-slate-100 transition focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- E-Jalin Brand Logo inspired by SiTIA layout -->
                    <a href="{{ url('/dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-[#0f2a71] border-2 border-[#ffb700] flex items-center justify-center text-[#ffb700] font-black text-xl shadow-xs">
                            EJ
                        </div>
                        <div class="leading-none">
                            <span class="text-2xl font-black tracking-tighter text-[#0f2a71]">
                                E<span class="text-[#ffb700]">-JALIN</span>
                            </span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block mt-0.5">
                                JALIN JATIM
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Center/Left: Tahun Anggaran Pill -->
                <div class="hidden md:flex items-center gap-2">
                    <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-lg border border-slate-300 bg-slate-50 text-xs font-semibold text-slate-700">
                        <span>Tahun Anggaran :</span>
                        <select class="bg-transparent font-bold text-[#0f2a71] focus:outline-none cursor-pointer">
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                        </select>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Right: User Identity & Actions -->
                <div class="flex items-center gap-3">
                    <!-- User Profile Dropdown Pill -->
                    <div class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs">
                        <div class="w-7 h-7 rounded-full bg-[#0f2a71] text-[#ffb700] flex items-center justify-center font-bold text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <span class="font-bold text-[#0f2a71] block leading-tight">
                                {{ Auth::user()->name ?? 'Administrator E-Jalin' }}
                            </span>
                            <span class="text-[10px] text-slate-500 font-medium">Dinas PU Bina Marga Prov. Jatim</span>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar dari sistem" class="p-2 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 border border-slate-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Body Container: Sidebar + Content -->
    <div class="flex flex-grow relative overflow-hidden">
        
        <!-- Sidebar Navigation (Deep Navy Style) -->
        <aside id="sidebar" class="w-64 bg-[#09183f] text-slate-300 flex-shrink-0 flex flex-col border-r border-[#0f2a71] transition-all duration-300 ease-in-out">
            <div class="p-4 flex-grow overflow-y-auto space-y-6">
                
                <!-- Main Menu Group: Usulan PEMDA -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 px-3 py-1">
                        <div class="flex items-center gap-2 text-white">
                            <svg class="w-4 h-4 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span>Usulan PEMDA</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <!-- Submenu Items -->
                    <div class="space-y-1 text-xs font-medium pl-3 border-l-2 border-[#0f2a71] ml-4">
                        <a href="{{ route('usulan-inpres') }}" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-[#ffb700] hover:bg-white/5 transition">
                            Usulan Inpres
                        </a>
                        <!-- Active Submenu: Referensi Ruas -->
                        <a href="{{ url('/dashboard') }}" class="block py-2 px-3 rounded-lg bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm">
                            Referensi Ruas
                        </a>
                        <a href="{{ route('referensi-jembatan') }}" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-[#ffb700] hover:bg-white/5 transition">
                            Referensi Jembatan
                        </a>
                        <!-- <a href="#" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition">
                            User Manual Usulan Inpres
                        </a>
                        <a href="#" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition">
                            Usulan Jembatan Gantung
                        </a>
                        <a href="#" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition">
                            Template Dokumen Data Dukung
                        </a> -->
                    </div>
                </div>

                <!-- Menu Group: Referensi Master -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 px-3 py-1">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3s3.5-1 3.5-3V7c0-2-1.5-3-3.5-3S4 5 4 7zm9 0v10c0 2 1.5 3 3.5 3s3.5-1 3.5-3V7c0-2-1.5-3-3.5-3s-3.5 1-3.5 3z"></path>
                            </svg>
                            <span>Referensi Master</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <div class="space-y-1 text-xs font-medium pl-3 border-l-2 border-[#0f2a71] ml-4">
                        <a href="#" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition">
                            Data 38 Kab/Kota
                        </a>
                        <a href="#" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition">
                            Daftar UPT PJJ
                        </a>
                    </div>
                </div>

                <!-- Menu Group: Monitoring & Integrasi -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 px-3 py-1">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            <span>Geospasial GIS</span>
                        </div>
                    </div>
                    <div class="space-y-1 text-xs font-medium pl-3 border-l-2 border-[#0f2a71] ml-4">
                        <a href="{{ url('/#geospasial') }}" target="_blank" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition">
                            Peta Spasial Jatim
                        </a>
                        <a href="http://localhost:8080" target="_blank" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-[#ffb700] hover:bg-white/5 transition flex items-center justify-between">
                            <span>phpMyAdmin</span>
                            <span class="text-[10px] bg-[#ffb700] text-[#0f2a71] px-1.5 py-0.2 rounded font-bold">DB</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Sidebar Footer Status -->
            <div class="p-3.5 bg-[#071333] border-t border-[#0f2a71] text-[11px] text-slate-400 flex items-center justify-between">
                <span>E-Jalin v12.0</span>
                <span class="flex items-center gap-1.5 text-emerald-400 font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Online
                </span>
            </div>
        </aside>

        <!-- Main Page Content Area -->
        <main class="flex-grow p-4 sm:p-6 lg:p-8 overflow-y-auto">
            
            <!-- Breadcrumbs -->
            <nav class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 mb-6 gap-2">
                <span class="text-[#0f2a71] hover:underline cursor-pointer">USULAN PEMDA</span>
                <span class="text-slate-400">&gt;</span>
                <span class="text-[#b87500]">REFERENSI RUAS</span>
            </nav>

            <!-- Action Bar: Add, Upload SK, View Map -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-6">
                <!-- Left Action: Tambah Ruas -->
                <div>
                    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="inline-flex items-center justify-center gap-1.5 px-4.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-sm transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Tambah RUAS</span>
                    </button>
                </div>

                <!-- Right Actions: Upload SK & View Map -->
                <div class="flex items-center gap-2.5">
                    <button class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#ffb700] hover:bg-[#e69d00] text-[#0f2a71] font-black text-xs sm:text-sm shadow-sm transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        <span>Upload SK Ruas Jalan</span>
                    </button>

                    <button title="Lihat Peta Spasial" class="p-2.5 rounded-xl bg-[#0f2a71] hover:bg-[#1a3788] text-[#ffb700] shadow-sm transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Table Card Container -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                
                <!-- Table Controls (Page Size & Search) -->
                <div class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 text-xs">
                    <!-- Page Size -->
                    <div class="flex items-center gap-2 text-slate-600 font-medium">
                        <select id="pageSize" class="border border-slate-300 rounded-lg px-2.5 py-1.5 bg-slate-50 font-bold text-[#0f2a71] focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>data per halaman</span>
                    </div>

                    <!-- Search Input -->
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-700">Cari:</span>
                        <div class="relative">
                            <input 
                                id="searchInput"
                                type="text" 
                                placeholder="Ketik nama / kode ruas..."
                                class="border border-slate-300 rounded-lg pl-3 pr-8 py-1.5 w-60 sm:w-72 bg-slate-50/50 text-slate-800 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                            >
                            <svg class="w-4 h-4 text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Responsive Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700" id="ruasTable">
                        <thead class="bg-slate-50/80 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200 select-none">
                            <tr>
                                <th class="py-3.5 px-4 cursor-pointer hover:text-[#0f2a71] transition">
                                    <div class="flex items-center justify-between">
                                        <span>KODE RUAS</span>
                                        <span class="text-slate-400 text-[10px]">⇅</span>
                                    </div>
                                </th>
                                <th class="py-3.5 px-4 cursor-pointer hover:text-[#0f2a71] transition">
                                    <div class="flex items-center justify-between">
                                        <span>NAMA RUAS</span>
                                        <span class="text-slate-400 text-[10px]">⇅</span>
                                    </div>
                                </th>
                                <th class="py-3.5 px-4 cursor-pointer hover:text-[#0f2a71] transition">
                                    <div class="flex items-center justify-between">
                                        <span>KAB/KOTA</span>
                                        <span class="text-slate-400 text-[10px]">⇅</span>
                                    </div>
                                </th>
                                <th class="py-3.5 px-4 cursor-pointer hover:text-[#0f2a71] transition">
                                    <div class="flex items-center justify-between">
                                        <span>STATUS</span>
                                        <span class="text-slate-400 text-[10px]">⇅</span>
                                    </div>
                                </th>
                                <th class="py-3.5 px-4 cursor-pointer hover:text-[#0f2a71] transition">
                                    <div class="flex items-center justify-between">
                                        <span>SUMBER</span>
                                        <span class="text-slate-400 text-[10px]">⇅</span>
                                    </div>
                                </th>
                                <th class="py-3.5 px-4 text-center">
                                    ACTION
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <!-- Row 1 -->
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">28ZZQ3B</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">RUAS JALAN KRESIKAN - DAWUNG</td>
                                <td class="py-3 px-4 uppercase text-slate-600">KABUPATEN TULUNGAGUNG</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        Kabupaten/Kota
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 2 -->
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">28ZZGXC</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">RUAS JALAN DESA DUWET</td>
                                <td class="py-3 px-4 uppercase text-slate-600">KABUPATEN TULUNGAGUNG</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        Kabupaten/Kota
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 3 -->
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">28ZYX6K</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">KAMAL - KWANYAR (R.51)</td>
                                <td class="py-3 px-4 uppercase text-slate-600">KABUPATEN BANGKALAN</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        Kabupaten/Kota
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 4 -->
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">28ZQBWL</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">PEJATEN - TEGALAMPEL</td>
                                <td class="py-3 px-4 uppercase text-slate-600">KABUPATEN BONDOWOSO</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        Kabupaten/Kota
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 5 (Provinsi) -->
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">28ZPR76</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">SRONO - MUNCAR</td>
                                <td class="py-3 px-4 uppercase text-slate-600">KABUPATEN BANYUWANGI</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-[#0f2a71] text-[#ffb700] text-[11px] font-bold">
                                        Provinsi
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 6 -->
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">28ZOTCT</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">JL. DIPONEGORO</td>
                                <td class="py-3 px-4 uppercase text-slate-600">KOTA BLITAR</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        Kabupaten/Kota
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 7 -->
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">28ZMPVB</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">DALAM KOTA SANGKAPURA</td>
                                <td class="py-3 px-4 uppercase text-slate-600">KABUPATEN GRESIK</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        Kabupaten/Kota
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 8 (Provinsi) -->
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">28ZM6TE</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">KEJAYAN - TOSARI</td>
                                <td class="py-3 px-4 uppercase text-slate-600">KABUPATEN PASURUAN</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-[#0f2a71] text-[#ffb700] text-[11px] font-bold">
                                        Provinsi
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 9 -->
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">28ZHXGU</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">RUAS JALAN BESUKI - BANTENGAN</td>
                                <td class="py-3 px-4 uppercase text-slate-600">KABUPATEN TULUNGAGUNG</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        Kabupaten/Kota
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 sm:p-5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                    <div>
                        Menampilkan <span class="font-bold text-slate-800">1</span> sampai <span class="font-bold text-slate-800">9</span> dari <span class="font-bold text-slate-800">4,281</span> data ruas
                    </div>

                    <div class="flex items-center gap-1 font-semibold">
                        <button class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 disabled:opacity-50" disabled>Sebelumnya</button>
                        <button class="px-3 py-1.5 rounded-lg bg-[#0f2a71] text-[#ffb700] font-black border border-[#0f2a71]">1</button>
                        <button class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100">2</button>
                        <button class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100">3</button>
                        <span class="px-2">...</span>
                        <button class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100">429</button>
                        <button class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100">Selanjutnya</button>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <!-- Modal Tambah Ruas (Persis SiTIA dengan Style E-Jalin) -->
    <div id="addModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl border-2 border-slate-200 overflow-hidden my-8">
            
            <!-- Modal Header -->
            <div class="bg-[#0f2a71] px-6 py-4 flex items-center justify-between border-b-4 border-[#ffb700] text-white">
                <h3 class="font-black text-sm tracking-wider uppercase flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ffb700] animate-pulse"></span>
                    TAMBAH DATA
                </h3>
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-slate-300 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Form Body -->
            <form id="formTambahRuas" class="p-6 sm:p-7 space-y-4.5 text-xs">
                
                <!-- 1. Provinsi -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <select id="provinsi" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition">
                        <option value="" disabled selected>--Pilih--</option>
                        <option value="JAWA TIMUR" selected>JAWA TIMUR</option>
                    </select>
                </div>

                <!-- 2. Kab/Kota -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        Kab/Kota <span class="text-red-500">*</span>
                    </label>
                    <select id="kabKota" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition">
                        <option value="" disabled selected>--Pilih--</option>
                        <option value="KABUPATEN TULUNGAGUNG">KABUPATEN TULUNGAGUNG</option>
                        <option value="KABUPATEN BANGKALAN">KABUPATEN BANGKALAN</option>
                        <option value="KABUPATEN BONDOWOSO">KABUPATEN BONDOWOSO</option>
                        <option value="KABUPATEN BANYUWANGI">KABUPATEN BANYUWANGI</option>
                        <option value="KOTA BLITAR">KOTA BLITAR</option>
                        <option value="KABUPATEN GRESIK">KABUPATEN GRESIK</option>
                        <option value="KABUPATEN PASURUAN">KABUPATEN PASURUAN</option>
                        <option value="KABUPATEN MALANG">KABUPATEN MALANG</option>
                        <option value="KOTA SURABAYA">KOTA SURABAYA</option>
                        <option value="KABUPATEN SIDOARJO">KABUPATEN SIDOARJO</option>
                        <option value="KABUPATEN MOJOKERTO">KABUPATEN MOJOKERTO</option>
                        <option value="KABUPATEN JOMBANG">KABUPATEN JOMBANG</option>
                        <option value="KABUPATEN MADIUN">KABUPATEN MADIUN</option>
                        <option value="KABUPATEN BOJONEGORO">KABUPATEN BOJONEGORO</option>
                        <option value="KABUPATEN TUBAN">KABUPATEN TUBAN</option>
                        <option value="KABUPATEN LAMONGAN">KABUPATEN LAMONGAN</option>
                        <option value="KABUPATEN NGANJUK">KABUPATEN NGANJUK</option>
                        <option value="KABUPATEN KEDIRI">KABUPATEN KEDIRI</option>
                        <option value="KABUPATEN PACITAN">KABUPATEN PACITAN</option>
                        <option value="KABUPATEN PONOROGO">KABUPATEN PONOROGO</option>
                        <option value="KABUPATEN TRENGGALEK">KABUPATEN TRENGGALEK</option>
                        <option value="KABUPATEN PROBOLINGGO">KABUPATEN PROBOLINGGO</option>
                        <option value="KABUPATEN LUMAJANG">KABUPATEN LUMAJANG</option>
                        <option value="KABUPATEN JEMBER">KABUPATEN JEMBER</option>
                        <option value="KABUPATEN SITUBONDO">KABUPATEN SITUBONDO</option>
                        <option value="KABUPATEN SAMPANG">KABUPATEN SAMPANG</option>
                        <option value="KABUPATEN PAMEKASAN">KABUPATEN PAMEKASAN</option>
                        <option value="KABUPATEN SUMENEP">KABUPATEN SUMENEP</option>
                    </select>
                </div>

                <!-- 3. Nama Ruas -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        Nama Ruas <span class="text-red-500">*</span>
                    </label>
                    <input 
                        id="namaRuas"
                        type="text" 
                        required 
                        placeholder="Contoh: RUAS JALAN KRESIKAN - DAWUNG"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                    >
                </div>

                <!-- 4. Panjang Ruas (KM) -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        Panjang Ruas (KM), <span class="font-normal italic text-slate-500">contoh: 7.55</span> <span class="text-red-500">*</span>
                    </label>
                    <input 
                        id="panjangRuas"
                        type="text" 
                        required 
                        placeholder="Contoh: 7.55"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                    >
                </div>

                <!-- 5. Koordinat Ruas (4 Kolom) -->
                <div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <!-- Longitude X1 (Awal) -->
                        <div>
                            <label class="block font-bold text-slate-700 text-[11px] leading-tight">
                                Longitude X1 (Awal)
                            </label>
                            <span class="block text-[10px] text-slate-500 italic mb-1">
                                Contoh: 107.1051155304269
                            </span>
                            <input 
                                id="longX1"
                                type="text" 
                                placeholder="112.xxxxxx"
                                class="w-full px-2.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                            >
                        </div>

                        <!-- Latitude Y1 (Awal) -->
                        <div>
                            <label class="block font-bold text-slate-700 text-[11px] leading-tight">
                                Latitude Y1 (Awal)
                            </label>
                            <span class="block text-[10px] text-slate-500 italic mb-1">
                                Contoh: -6.295248141177142
                            </span>
                            <input 
                                id="latY1"
                                type="text" 
                                placeholder="-7.xxxxxx"
                                class="w-full px-2.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                            >
                        </div>

                        <!-- Longitude X2 (Akhir) -->
                        <div>
                            <label class="block font-bold text-slate-700 text-[11px] leading-tight">
                                Longitude X2 (Akhir)
                            </label>
                            <span class="block text-[10px] text-slate-500 italic mb-1">
                                Contoh: 107.1051155304269
                            </span>
                            <input 
                                id="longX2"
                                type="text" 
                                placeholder="112.xxxxxx"
                                class="w-full px-2.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                            >
                        </div>

                        <!-- Latitude Y2 (Akhir) -->
                        <div>
                            <label class="block font-bold text-slate-700 text-[11px] leading-tight">
                                Latitude Y2 (Akhir)
                            </label>
                            <span class="block text-[10px] text-slate-500 italic mb-1">
                                Contoh: -6.295248141177142
                            </span>
                            <input 
                                id="latY2"
                                type="text" 
                                placeholder="-7.xxxxxx"
                                class="w-full px-2.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                            >
                        </div>
                    </div>

                    <!-- Note bawah koordinat -->
                    <p class="text-center text-[11px] text-emerald-600 font-medium italic mt-2.5">
                        Data koordinat dapat disusulkan atau diedit pada tahap berikutnya
                    </p>
                </div>

                <!-- 6. Status -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="statusRuas" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition">
                        <option value="" disabled selected>--Pilih--</option>
                        <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Nasional">Nasional</option>
                    </select>
                </div>

                <!-- 7. Upload Peta (KML atau KMZ) -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        Upload Peta (KML atau KMZ) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center">
                        <input 
                            id="filePeta"
                            type="file" 
                            accept=".kml,.kmz"
                            required
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 file:cursor-pointer cursor-pointer border border-slate-300 rounded-lg p-1 bg-white"
                        >
                    </div>
                </div>

                <!-- Modal Footer Buttons -->
                <div class="pt-5 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button 
                        type="button" 
                        onclick="document.getElementById('addModal').classList.add('hidden')" 
                        class="px-5 py-2.5 rounded-lg border border-slate-300 font-semibold text-slate-700 hover:bg-slate-100 transition active:scale-95 text-xs"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-lg bg-[#0f2a71] text-[#ffb700] hover:bg-[#153894] font-black shadow-md shadow-[#0f2a71]/20 transition flex items-center gap-1.5 active:scale-95 text-xs"
                    >
                        <svg class="w-4 h-4 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Client-side Interactive Search & Sidebar Toggle Scripts -->
    <script>
        // Real-time table search filter
        const searchInput = document.getElementById('searchInput');
        const ruasTable = document.getElementById('ruasTable');
        const tbody = ruasTable.getElementsByTagName('tbody')[0];

        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const rows = tbody.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const text = rows[i].textContent.toLowerCase();
                rows[i].style.display = text.includes(query) ? '' : 'none';
            }
        });

        // Responsive Sidebar Toggle
        const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
        const sidebar = document.getElementById('sidebar');

        toggleSidebarBtn.addEventListener('click', function() {
            sidebar.classList.toggle('-ml-64');
        });

        // Handle Form Tambah Ruas Submit
        const formTambahRuas = document.getElementById('formTambahRuas');
        formTambahRuas.addEventListener('submit', function(e) {
            e.preventDefault();

            const nama = document.getElementById('namaRuas').value.toUpperCase();
            const kabKota = document.getElementById('kabKota').value;
            const status = document.getElementById('statusRuas').value;
            const randomCode = '28Z' + Math.random().toString(36).substring(2, 6).toUpperCase();

            const statusBadge = status === 'Provinsi' 
                ? '<span class="px-2 py-0.5 rounded bg-[#0f2a71] text-[#ffb700] text-[11px] font-bold">Provinsi</span>'
                : '<span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">' + status + '</span>';

            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-blue-50/40 transition bg-emerald-50/50 animate-pulse';
            newRow.innerHTML = `
                <td class="py-3 px-4 font-mono font-bold text-[#0f2a71]">${randomCode}</td>
                <td class="py-3 px-4 font-semibold text-slate-900">${nama}</td>
                <td class="py-3 px-4 uppercase text-slate-600">${kabKota}</td>
                <td class="py-3 px-4">${statusBadge}</td>
                <td class="py-3 px-4 text-slate-500">Entri Satker</td>
                <td class="py-3 px-4 text-center">
                    <div class="inline-flex items-center gap-1.5">
                        <button title="Ubah Ruas" class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3788] flex items-center justify-center transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </button>
                        <button title="Unduh Data" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            `;

            tbody.insertBefore(newRow, tbody.firstChild);

            setTimeout(() => {
                newRow.classList.remove('bg-emerald-50/50', 'animate-pulse');
            }, 2000);

            // Close modal & reset
            document.getElementById('addModal').classList.add('hidden');
            formTambahRuas.reset();

            alert('Data ruas jalan baru "' + nama + '" berhasil disimpan ke dalam daftar referensi ruas!');
        });
    </script>

</body>
</html>
