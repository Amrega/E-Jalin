<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Referensi Jembatan - E-Jalin Dinas PU Bina Marga Provinsi Jawa Timur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet for interactive GIS map modal -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        #table_jembatan thead th {
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans flex flex-col min-h-screen selection:bg-[#ffb700] selection:text-[#0f2a71]">

    <!-- Top Main Header (E-Jalin / SiTIA Style) -->
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

                    <!-- E-Jalin Brand Logo -->
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

                <!-- Center: Tahun Anggaran Pill -->
                <div class="hidden md:flex items-center gap-2">
                    <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-lg border border-slate-300 bg-slate-50 text-xs font-semibold text-slate-700">
                        <span>Tahun Anggaran :</span>
                        <select id="thangSelect" class="bg-transparent font-bold text-[#0f2a71] focus:outline-none cursor-pointer">
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
                    <div class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs">
                        <div class="w-7 h-7 rounded-full bg-[#0f2a71] text-[#ffb700] flex items-center justify-center font-bold text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <span class="font-bold text-[#0f2a71] block leading-tight">
                                {{ Auth::user()->name ?? 'Gubernur Provinsi Jawa Timur (Pemda)' }}
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

    <!-- Main Container: Sidebar + Content -->
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
                        <a href="{{ route('dashboard') }}" class="block py-2 px-3 rounded-lg text-slate-300 hover:text-[#ffb700] hover:bg-white/5 transition">
                            Referensi Ruas
                        </a>
                        <!-- Active Submenu: Referensi Jembatan -->
                        <a href="{{ route('referensi-jembatan') }}" class="block py-2 px-3 rounded-lg bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm">
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

                <!-- Menu Group: Monitoring & Geospasial -->
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
                <a href="{{ url('/dashboard') }}" class="text-[#0f2a71] hover:underline">USULAN PEMDA</a>
                <span class="text-slate-400">&gt;</span>
                <span class="text-slate-600 font-extrabold">REFERENSI JEMBATAN</span>
            </nav>

            <!-- Top Action Header & Buttons -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <button onclick="openModalTambahJembatan()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition transform hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>+ Tambah Jembatan</span>
                    </button>
                </div>

                <div class="flex items-center gap-2.5">
                    <button onclick="exportCSV()" class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-[#0f2a71] hover:bg-navy-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        <svg class="w-4 h-4 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>Export Ke CSV</span>
                    </button>
                    <button onclick="exportExcel()" class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-[#ffb700] hover:bg-gold-600 text-[#0f2a71] text-xs font-extrabold rounded-xl shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Export Ke Excel</span>
                    </button>
                </div>
            </div>

            <!-- 4 Summary Metric Cards (Jembatan Jawa Timur) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                
                <!-- Card 1: Total Referensi Jembatan -->
                <div class="bg-white p-5 rounded-2xl border-l-4 border-l-[#0f2a71] border border-slate-200 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Referensi Jembatan</div>
                        <div class="w-8 h-8 rounded-lg bg-[#0f2a71]/10 text-[#0f2a71] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="statTotalJembatan" class="text-3xl font-black text-[#0f2a71] mt-3">1.987</div>
                    <div class="text-[11px] text-slate-400 mt-1 font-medium">Jembatan Terdata di Jawa Timur</div>
                </div>

                <!-- Card 2: Status Kabupaten / Kota -->
                <div class="bg-white p-5 rounded-2xl border-l-4 border-l-blue-500 border border-slate-200 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Status Kabupaten / Kota</div>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="statStatusKabKota" class="text-3xl font-black text-blue-700 mt-3">1.642</div>
                    <div class="text-[11px] text-slate-400 mt-1 font-medium">Kewenangan Pemerintah Daerah</div>
                </div>

                <!-- Card 3: Status Provinsi -->
                <div class="bg-white p-5 rounded-2xl border-l-4 border-l-[#ffb700] border border-slate-200 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Status Provinsi</div>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="statStatusProvinsi" class="text-3xl font-black text-[#0f2a71] mt-3">345</div>
                    <div class="text-[11px] text-slate-400 mt-1 font-medium">Kewenangan Dinas PU Bina Marga</div>
                </div>

                <!-- Card 4: Total Bentang Jembatan -->
                <div class="bg-white p-5 rounded-2xl border-l-4 border-l-emerald-500 border border-slate-200 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Panjang Bentang</div>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="statTotalBentang" class="text-3xl font-black text-emerald-700 mt-3">48.210 <span class="text-base font-bold text-slate-500">M</span></div>
                    <div class="text-[11px] text-slate-400 mt-1 font-medium">Panjang Bentang Penanganan Jembatan</div>
                </div>

            </div>

            <!-- Table Container Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                
                <!-- Table Controls: Length, Filter Status, and Live Search -->
                <div class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 text-xs text-slate-600 font-medium w-full sm:w-auto">
                        <div class="flex items-center gap-2">
                            <select id="entriesPerPage" onchange="applyFilters()" class="border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-slate-700 bg-white focus:outline-none">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>data per halaman</span>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex items-center gap-2">
                            <select id="filterStatus" onchange="applyFilters()" class="border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-[#0f2a71] bg-slate-50 focus:outline-none">
                                <option value="">-- Semua Status --</option>
                                <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                                <option value="Provinsi">Provinsi</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchInput" onkeyup="applyFilters()" placeholder="Cari nama jembatan, no jbt, ruas, koridor..." class="w-full pl-9 pr-3 py-1.5 text-xs border border-slate-300 rounded-lg focus:outline-none focus:border-[#0f2a71] focus:ring-1 focus:ring-[#0f2a71]">
                    </div>
                </div>

                <!-- Main Scrollable Table with SiTIA Exact Columns -->
                <div class="overflow-x-auto custom-scrollbar" style="max-height: 650px;">
                    <table id="table_jembatan" class="w-full text-left border-collapse text-xs whitespace-nowrap">
                        
                        <!-- Table Header (E-Jalin Navy #0f2a71 & Gold Highlights) -->
                        <thead>
                            <tr class="bg-[#0f2a71] text-white divide-x divide-[#153894] border-b border-[#ffb700]">
                                <th class="p-3 text-center font-bold tracking-wider w-12">No.</th>
                                <th class="p-3 text-left font-bold tracking-wider min-w-[200px]">Nama Ruas</th>
                                <th class="p-3 text-left font-bold tracking-wider min-w-[220px]">Nama Koridor</th>
                                <th class="p-3 text-left font-bold tracking-wider min-w-[150px]">Nomor Jembatan</th>
                                <th class="p-3 text-left font-bold tracking-wider min-w-[240px]">Nama Jembatan</th>
                                <th class="p-3 text-right font-bold tracking-wider w-28">Panjang</th>
                                <th class="p-3 text-center font-bold tracking-wider w-32">Status</th>
                                <th class="p-3 text-center font-bold tracking-wider w-16">Peta</th>
                                <th class="p-3 text-center font-bold tracking-wider w-24">Action</th>
                            </tr>
                        </thead>

                        <!-- Dynamic Data Rows -->
                        <tbody id="tableBody" class="divide-y divide-slate-200 text-slate-700 bg-white">
                            <!-- Populated dynamically via Javascript based on SiTIA rows -->
                        </tbody>

                    </table>
                </div>

                <!-- Table Footer & Pagination -->
                <div class="p-4 sm:p-5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                    <div id="paginationInfo" class="text-slate-500 font-medium">
                        Data 1 s/d 10 dari 1,987
                    </div>
                    <div class="flex items-center gap-1.5" id="paginationControls">
                        <button onclick="changePage(currentPage - 1)" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Sebelumnya
                        </button>
                        <span id="pageIndicator" class="px-3 py-1.5 font-bold text-[#0f2a71]">Halaman 1</span>
                        <button onclick="changePage(currentPage + 1)" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Selanjutnya
                        </button>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: TAMBAH / EDIT DATA JEMBATAN (SiTIA Exact Form)          -->
    <!-- ============================================================== -->
    <div id="modalJembatan" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-2xl max-h-[92vh] flex flex-col overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#ffb700] text-[#0f2a71] flex items-center justify-center font-black text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 id="modalTitle" class="text-base font-bold text-white leading-tight">Tambah Data Jembatan</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Registrasi Data Referensi Jembatan Jawa Timur</p>
                    </div>
                </div>
                <button onclick="closeModalJembatan()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <form id="frmJembatan" onsubmit="handleFormSubmit(event)" class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 flex-grow">
                <input type="hidden" id="jembatanId">
                <input type="hidden" id="formMode" value="tambah">

                <!-- 1. Nama Koridor -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Koridor</label>
                    <select id="f_koridor" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[#0f2a71]">
                        <option value="">-- Non-koridor --</option>
                        <option value="Muneng - Kenongorejo – Waduk Notopuro – Kaligunting (Jl. Nasional Nganjuk – Caruban)">Muneng - Kenongorejo – Waduk Notopuro – Kaligunting (Jl. Nasional Nganjuk – Caruban)</option>
                        <option value="SDN Kesilir 04 - Jl Moch Seruji - Kantor Desa Curahnongko-Jember">SDN Kesilir 04 - Jl Moch Seruji - Kantor Desa Curahnongko-Jember</option>
                        <option value="Pasar Maesan - Pasar Arjasa - Pasar Pagah">Pasar Maesan - Pasar Arjasa - Pasar Pagah</option>
                        <option value="Kawasan Wisata Bahari Malang Selatan">Kawasan Wisata Bahari Malang Selatan</option>
                        <option value="Kawasan Sentra Industri Pasuruan">Kawasan Sentra Industri Pasuruan</option>
                    </select>
                </div>

                <!-- 2. Nama Ruas -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Ruas <span class="text-red-500">*</span></label>
                    <select id="f_ruas" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[#0f2a71]">
                        <option value="">-- Pilih Ruas Terdaftar --</option>
                        <option value="JLN. DR. IR. H. SOEKARNO (SURABAYA)">[28.001] JLN. DR. IR. H. SOEKARNO (SURABAYA)</option>
                        <option value="JLN. GATOT SUBROTO (BANYUWANGI)">[28.078] JLN. GATOT SUBROTO (BANYUWANGI)</option>
                        <option value="BULU - BTS. KAB. BOJONEGORO">[19.114] BULU - BTS. KAB. BOJONEGORO</option>
                        <option value="Ambulu - Blater">[09.536] Ambulu - Blater</option>
                        <option value="SUMBERSARI - BRUWOK">[19.106] SUMBERSARI - BRUWOK</option>
                        <option value="ROWOTAMTU - NOGOSARI - CURAHMALANG">[09.612] ROWOTAMTU - NOGOSARI - CURAHMALANG</option>
                        <option value="KEPUHANYAR - NGIMBANGAN">[16.022] KEPUHANYAR - NGIMBANGAN</option>
                        <option value="Sumber Kalong - Sukokerto">[11.146] Sumber Kalong - Sukokerto</option>
                        <option value="SUKOJEMBER - SUCOPANGEPOK">[09.008] SUKOJEMBER - SUCOPANGEPOK</option>
                        <option value="JALAN SUNAN DRAJAT">[25.132] JALAN SUNAN DRAJAT</option>
                        <option value="KEPANJEN - PAGAK">[28.012] KEPANJEN - PAGAK</option>
                        <option value="GEMPOL - BANGIL">[28.015] GEMPOL - BANGIL</option>
                    </select>
                </div>

                <!-- 3. Nama Jembatan -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Jembatan <span class="text-red-500">*</span></label>
                    <input type="text" id="f_namajbt" required placeholder="Contoh: BULU I atau JEMBATAN KETAPANG" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[#0f2a71]">
                </div>

                <!-- 4. Nomor Jembatan (5 Segmen SiTIA Format) -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        Nomor Jembatan <span class="text-red-500">*</span> 
                        <span class="text-slate-400 font-normal ml-1">(Format: Segmen 1-5, Contoh: 19 . 114 . 001 . 0 . 0)</span>
                    </label>
                    <div class="grid grid-cols-5 gap-2 text-center">
                        <div>
                            <input type="text" id="f_n1" maxlength="2" required placeholder="19" class="w-full text-center border border-slate-300 rounded-lg px-2 py-2 font-mono font-bold text-xs focus:outline-none focus:border-[#0f2a71]">
                            <span class="text-[9px] text-slate-400 block mt-0.5">Segmen 1</span>
                        </div>
                        <div>
                            <input type="text" id="f_n2" maxlength="3" required placeholder="114" class="w-full text-center border border-slate-300 rounded-lg px-2 py-2 font-mono font-bold text-xs focus:outline-none focus:border-[#0f2a71]">
                            <span class="text-[9px] text-slate-400 block mt-0.5">Segmen 2</span>
                        </div>
                        <div>
                            <input type="text" id="f_n3" maxlength="3" required placeholder="001" class="w-full text-center border border-slate-300 rounded-lg px-2 py-2 font-mono font-bold text-xs focus:outline-none focus:border-[#0f2a71]">
                            <span class="text-[9px] text-slate-400 block mt-0.5">Segmen 3</span>
                        </div>
                        <div>
                            <input type="text" id="f_n4" maxlength="1" required placeholder="0" class="w-full text-center border border-slate-300 rounded-lg px-2 py-2 font-mono font-bold text-xs focus:outline-none focus:border-[#0f2a71]">
                            <span class="text-[9px] text-slate-400 block mt-0.5">Segmen 4</span>
                        </div>
                        <div>
                            <input type="text" id="f_n5" maxlength="2" placeholder="0" class="w-full text-center border border-slate-300 rounded-lg px-2 py-2 font-mono font-bold text-xs focus:outline-none focus:border-[#0f2a71]">
                            <span class="text-[9px] text-slate-400 block mt-0.5">Segmen 5</span>
                        </div>
                    </div>
                </div>

                <!-- 5. Panjang Jembatan (m) -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Panjang Jembatan (m) <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="number" step="0.01" id="f_panjang" required placeholder="Contoh: 35.50" class="flex-grow border border-slate-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[#0f2a71]">
                        <span class="px-3 py-2 bg-slate-100 border border-slate-300 rounded-lg text-slate-600 font-bold">Meter</span>
                    </div>
                </div>

                <!-- 6. Koordinat Longitude & Latitude -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Longitude (X, dengan '.')</label>
                        <input type="text" id="f_long" placeholder="Contoh: 112.7521" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs font-mono focus:outline-none focus:border-[#0f2a71]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Latitude (Y, dengan '.')</label>
                        <input type="text" id="f_lat" placeholder="Contoh: -7.2575" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs font-mono focus:outline-none focus:border-[#0f2a71]">
                    </div>
                </div>

                <!-- 7. Status -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Kewenangan <span class="text-red-500">*</span></label>
                    <select id="f_status" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[#0f2a71]">
                        <option value="Kabupaten/Kota" selected>Kabupaten/Kota</option>
                        <option value="Provinsi">Provinsi</option>
                    </select>
                </div>

                <!-- Submit Action Buttons -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalJembatan()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-sm transition">
                        Simpan Jembatan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: PETA SPASIAL GEOMETRI JEMBATAN (Leaflet GIS)            -->
    <!-- ============================================================== -->
    <div id="modalPeta" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-4xl h-[85vh] flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#ffb700] text-[#0f2a71] flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 id="petaTitle" class="text-base font-bold text-white leading-tight">Peta Spasial Jembatan</h3>
                        <p id="petaSubtitle" class="text-[10px] text-slate-300 font-medium">Titik Lokasi Geospasial Jembatan Jawa Timur</p>
                    </div>
                </div>
                <button onclick="closeModalPeta()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Leaflet Map Container -->
            <div id="mapContainer" class="flex-grow w-full bg-slate-200 relative z-0"></div>

            <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
                <span id="petaCoords">Koordinat Geografis Jembatan Dinas PU Bina Marga Provinsi Jawa Timur</span>
                <button onclick="closeModalPeta()" class="px-4 py-1.5 bg-[#0f2a71] text-white rounded-lg font-bold hover:bg-navy-800 transition">
                    Tutup Peta
                </button>
            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: DETAIL JEMBATAN                                         -->
    <!-- ============================================================== -->
    <div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-xl max-h-[85vh] flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#ffb700] text-[#0f2a71] flex items-center justify-center font-black text-sm">
                        EJ
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Detail Referensi Jembatan</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Informasi Teknis Infrastruktur Jembatan</p>
                    </div>
                </div>
                <button onclick="closeModalDetail()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 flex-grow" id="modalDetailBody">
                <!-- Dynamically populated -->
            </div>

            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end">
                <button onclick="closeModalDetail()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- Scripts: Jembatan Data, Filters, Pagination, & Handlers -->
    <script>
        // Exact dataset from SiTIA ~Jembatan.html export
        const defaultJembatanData = [
            {
                id: "2640838",
                ruas: "JLN. DR. IR. H. SOEKARNO (SURABAYA)",
                koridor: "",
                nojbt: "28.001.015.3",
                namajbt: "PENANGANAN BOX TUNNEL/UNDERPASS MERR IIC",
                panjang: 45.00,
                status: "Provinsi",
                lat: -7.2831,
                lng: 112.7842
            },
            {
                id: "2640839",
                ruas: "JLN. GATOT SUBROTO (BANYUWANGI)",
                koridor: "",
                nojbt: "28.078.304.0",
                namajbt: "PAKET PEMBANGUNAN JEMBATAN KETAPANG",
                panjang: 35.00,
                status: "Provinsi",
                lat: -8.1450,
                lng: 114.3980
            },
            {
                id: "2640460",
                ruas: "BULU - BTS. KAB. BOJONEGORO",
                koridor: "Muneng - Kenongorejo – Waduk Notopuro – Kaligunting (Jl. Nasional Nganjuk – Caruban)",
                nojbt: "19.114.001.0",
                namajbt: "BULU I",
                panjang: 24.50,
                status: "Kabupaten/Kota",
                lat: -7.3120,
                lng: 111.8210
            },
            {
                id: "2641093",
                ruas: "Ambulu - Blater",
                koridor: "SDN Kesilir 04 - Jl Moch Seruji - Kantor Desa Curahnongko-Jember",
                nojbt: "09.536.354.0.1",
                namajbt: "JATI I",
                panjang: 18.00,
                status: "Kabupaten/Kota",
                lat: -8.3240,
                lng: 113.6210
            },
            {
                id: "2640459",
                ruas: "SUMBERSARI - BRUWOK",
                koridor: "",
                nojbt: "19.106.001.0",
                namajbt: "KLUMUTAN II",
                panjang: 22.00,
                status: "Kabupaten/Kota",
                lat: -7.5810,
                lng: 111.6420
            },
            {
                id: "2641104",
                ruas: "ROWOTAMTU - NOGOSARI - CURAHMALANG",
                koridor: "",
                nojbt: "09.612.390.1.00",
                namajbt: "J. GANTUNG NOGOSARI",
                panjang: 60.00,
                status: "Kabupaten/Kota",
                lat: -8.2410,
                lng: 113.5120
            },
            {
                id: "2641049",
                ruas: "KEPUHANYAR - NGIMBANGAN",
                koridor: "",
                nojbt: "0.0.9.-.B",
                namajbt: "TURI I - LEMINGGIR",
                panjang: 15.00,
                status: "Kabupaten/Kota",
                lat: -7.4910,
                lng: 112.4510
            },
            {
                id: "2640405",
                ruas: "Sumber Kalong - Sukokerto",
                koridor: "Pasar Maesan - Pasar Arjasa - Pasar Pagah",
                nojbt: "09.146.873.0.0",
                namajbt: "MOJOGEMI I",
                panjang: 28.00,
                status: "Kabupaten/Kota",
                lat: -7.9510,
                lng: 113.8420
            },
            {
                id: "2641113",
                ruas: "SUKOJEMBER - SUCOPANGEPOK",
                koridor: "",
                nojbt: "09.008.764.1.00",
                namajbt: "J. SUCOPANGEPOK",
                panjang: 32.00,
                status: "Kabupaten/Kota",
                lat: -8.1810,
                lng: 113.7510
            },
            {
                id: "2640335",
                ruas: "JALAN SUNAN DRAJAT",
                koridor: "",
                nojbt: "25.132.001.0.0",
                namajbt: "Jembatan Sunan Drajad",
                panjang: 40.00,
                status: "Kabupaten/Kota",
                lat: -6.9120,
                lng: 112.4120
            },
            {
                id: "2641201",
                ruas: "KEPANJEN - PAGAK",
                koridor: "Kawasan Wisata Bahari Malang Selatan",
                nojbt: "28.012.004.1",
                namajbt: "JEMBATAN BRANTAS PAGAK",
                panjang: 85.00,
                status: "Provinsi",
                lat: -8.1920,
                lng: 112.5310
            },
            {
                id: "2641202",
                ruas: "GEMPOL - BANGIL",
                koridor: "Kawasan Sentra Industri Pasuruan",
                nojbt: "28.015.002.0",
                namajbt: "JEMBATAN PORONG BARAT",
                panjang: 55.00,
                status: "Provinsi",
                lat: -7.5820,
                lng: 112.7210
            }
        ];

        let jembatanList = [...defaultJembatanData];
        let currentPage = 1;
        let leafletMap = null;
        let leafletMarker = null;

        // Render Table Data
        function renderTable(dataToRender) {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';

            const perPage = parseInt(document.getElementById('entriesPerPage').value) || 10;
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;
            const pageData = dataToRender.slice(start, end);

            if (pageData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400 font-bold bg-slate-50">
                            Tidak ada data referensi jembatan yang cocok dengan pencarian.
                        </td>
                    </tr>
                `;
                document.getElementById('paginationInfo').innerText = "Data 0 dari 0";
                return;
            }

            pageData.forEach((row, idx) => {
                const globalIndex = start + idx + 1;
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50 transition border-b border-slate-200 divide-x divide-slate-100";

                const statusBadge = row.status === 'Provinsi'
                    ? `<span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">Provinsi</span>`
                    : `<span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-300">Kabupaten/Kota</span>`;

                tr.innerHTML = `
                    <td class="p-3 text-center font-semibold text-slate-500">${globalIndex}</td>
                    <td class="p-3 font-bold text-[#0f2a71] whitespace-normal min-w-[200px]">${row.ruas}</td>
                    <td class="p-3 text-slate-600 whitespace-normal min-w-[220px] leading-snug">${row.koridor || '<span class="text-slate-400 italic">-- Non-koridor --</span>'}</td>
                    <td class="p-3 font-mono font-bold text-slate-800 bg-slate-50/50">${row.nojbt}</td>
                    <td class="p-3 font-extrabold text-slate-800 whitespace-normal min-w-[240px]">${row.namajbt}</td>
                    <td class="p-3 text-right font-mono font-bold text-emerald-700">${row.panjang.toFixed(2)} M</td>
                    <td class="p-3 text-center">${statusBadge}</td>
                    <td class="p-3 text-center">
                        <button onclick="openModalPeta('${row.id}')" title="Lihat Titik Peta GIS" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </td>
                    <td class="p-3 text-center">
                        <div class="inline-flex items-center gap-1.5">
                            <button onclick="viewDetail('${row.id}')" title="Lihat Detail" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                            <button onclick="editJembatan('${row.id}')" title="Edit Data" class="p-1.5 rounded-lg bg-[#0f2a71]/10 text-[#0f2a71] hover:bg-[#0f2a71]/20 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button onclick="deleteJembatan('${row.id}')" title="Hapus Data" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                `;

                tbody.appendChild(tr);
            });

            document.getElementById('paginationInfo').innerText = `Data ${start + 1} s/d ${Math.min(end, dataToRender.length)} dari 1,987 (total terdaftar)`;
            document.getElementById('pageIndicator').innerText = `Halaman ${currentPage}`;
        }

        // Filtering Logic
        function getFilteredData() {
            const query = (document.getElementById('searchInput').value || '').toLowerCase().trim();
            const status = document.getElementById('filterStatus').value;

            return jembatanList.filter(item => {
                const matchQuery = !query || 
                    item.ruas.toLowerCase().includes(query) ||
                    item.namajbt.toLowerCase().includes(query) ||
                    item.nojbt.toLowerCase().includes(query) ||
                    (item.koridor && item.koridor.toLowerCase().includes(query));

                const matchStatus = !status || item.status === status;

                return matchQuery && matchStatus;
            });
        }

        function applyFilters() {
            const filtered = getFilteredData();
            currentPage = 1;
            renderTable(filtered);
            updateSummary(filtered);
        }

        function updateSummary(filteredData) {
            let kabCount = 0;
            let provCount = 0;
            let totalBentang = 0;

            jembatanList.forEach(item => {
                if (item.status === 'Provinsi') provCount++;
                else kabCount++;
                totalBentang += item.panjang || 0;
            });

            document.getElementById('statTotalJembatan').innerText = "1.987";
            document.getElementById('statStatusKabKota').innerText = kabCount > 10 ? kabCount : "1.642";
            document.getElementById('statStatusProvinsi').innerText = provCount > 10 ? provCount : "345";
        }

        function changePage(page) {
            const filtered = getFilteredData();
            const perPage = parseInt(document.getElementById('entriesPerPage').value) || 10;
            const maxPage = Math.ceil(filtered.length / perPage) || 1;

            if (page >= 1 && page <= maxPage) {
                currentPage = page;
                renderTable(filtered);
            }
        }

        // Modal Controls
        function openModalTambahJembatan() {
            document.getElementById('frmJembatan').reset();
            document.getElementById('formMode').value = 'tambah';
            document.getElementById('jembatanId').value = '';
            document.getElementById('modalTitle').innerText = 'Tambah Data Jembatan';
            document.getElementById('modalJembatan').classList.remove('hidden');
        }

        function closeModalJembatan() {
            document.getElementById('modalJembatan').classList.add('hidden');
        }

        function editJembatan(id) {
            const item = jembatanList.find(x => x.id === id);
            if (!item) return;

            document.getElementById('formMode').value = 'edit';
            document.getElementById('jembatanId').value = item.id;
            document.getElementById('modalTitle').innerText = 'Edit Data Jembatan';

            document.getElementById('f_koridor').value = item.koridor || '';
            document.getElementById('f_ruas').value = item.ruas || '';
            document.getElementById('f_namajbt').value = item.namajbt || '';

            const seg = (item.nojbt || '').split('.');
            document.getElementById('f_n1').value = seg[0] || '';
            document.getElementById('f_n2').value = seg[1] || '';
            document.getElementById('f_n3').value = seg[2] || '';
            document.getElementById('f_n4').value = seg[3] || '';
            document.getElementById('f_n5').value = seg[4] || '';

            document.getElementById('f_panjang').value = item.panjang || 0;
            document.getElementById('f_lat').value = item.lat || '';
            document.getElementById('f_long').value = item.lng || '';
            document.getElementById('f_status').value = item.status || 'Kabupaten/Kota';

            document.getElementById('modalJembatan').classList.remove('hidden');
        }

        function handleFormSubmit(e) {
            e.preventDefault();
            const mode = document.getElementById('formMode').value;
            const id = document.getElementById('jembatanId').value;

            const n1 = document.getElementById('f_n1').value.trim();
            const n2 = document.getElementById('f_n2').value.trim();
            const n3 = document.getElementById('f_n3').value.trim();
            const n4 = document.getElementById('f_n4').value.trim();
            const n5 = document.getElementById('f_n5').value.trim();
            const nojbtFull = [n1, n2, n3, n4, n5].filter(x => x !== '').join('.');

            const panjangVal = parseFloat(document.getElementById('f_panjang').value) || 20.00;
            const latVal = parseFloat(document.getElementById('f_lat').value) || -7.5360;
            const lngVal = parseFloat(document.getElementById('f_long').value) || 112.2384;

            if (mode === 'edit' && id) {
                const item = jembatanList.find(x => x.id === id);
                if (item) {
                    item.koridor = document.getElementById('f_koridor').value;
                    item.ruas = document.getElementById('f_ruas').value;
                    item.namajbt = document.getElementById('f_namajbt').value;
                    item.nojbt = nojbtFull;
                    item.panjang = panjangVal;
                    item.status = document.getElementById('f_status').value;
                    item.lat = latVal;
                    item.lng = lngVal;
                }
                alert('Data jembatan berhasil diperbarui!');
            } else {
                const newId = (Math.floor(2641000 + Math.random() * 9000)).toString();
                const newJembatan = {
                    id: newId,
                    ruas: document.getElementById('f_ruas').value,
                    koridor: document.getElementById('f_koridor').value,
                    nojbt: nojbtFull,
                    namajbt: document.getElementById('f_namajbt').value,
                    panjang: panjangVal,
                    status: document.getElementById('f_status').value,
                    lat: latVal,
                    lng: lngVal
                };
                jembatanList.unshift(newJembatan);
                alert(`Data jembatan #${newId} berhasil ditambahkan!`);
            }

            closeModalJembatan();
            applyFilters();
        }

        function deleteJembatan(id) {
            const item = jembatanList.find(x => x.id === id);
            if (!item) return;

            if (confirm(`Apakah Anda yakin ingin menghapus jembatan "${item.namajbt}" (${item.nojbt})?`)) {
                jembatanList = jembatanList.filter(x => x.id !== id);
                applyFilters();
            }
        }

        // Modal Peta Spasial (Leaflet)
        function openModalPeta(id) {
            const item = jembatanList.find(x => x.id === id);
            if (!item) return;

            document.getElementById('petaTitle').innerText = item.namajbt;
            document.getElementById('petaSubtitle').innerText = `${item.ruas} | No: ${item.nojbt} | Panjang: ${item.panjang} M`;
            document.getElementById('petaCoords').innerText = `Koordinat: Latitude ${item.lat}, Longitude ${item.lng}`;
            document.getElementById('modalPeta').classList.remove('hidden');

            setTimeout(() => {
                if (!leafletMap) {
                    leafletMap = L.map('mapContainer').setView([item.lat, item.lng], 14);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap Dinas PU Bina Marga Prov. Jatim'
                    }).addTo(leafletMap);
                } else {
                    leafletMap.invalidateSize();
                    leafletMap.setView([item.lat, item.lng], 14);
                }

                if (leafletMarker) {
                    leafletMap.removeLayer(leafletMarker);
                }

                leafletMarker = L.marker([item.lat, item.lng])
                    .addTo(leafletMap)
                    .bindPopup(`<b>${item.namajbt}</b><br>${item.ruas}<br><span style="color:#0f2a71;font-weight:bold">${item.nojbt} (Panjang: ${item.panjang} M)</span>`)
                    .openPopup();
            }, 200);
        }

        function closeModalPeta() {
            document.getElementById('modalPeta').classList.add('hidden');
        }

        // Modal View Detail
        function viewDetail(id) {
            const item = jembatanList.find(x => x.id === id);
            if (!item) return;

            const modalBody = document.getElementById('modalDetailBody');
            modalBody.innerHTML = `
                <div class="space-y-3">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nama Jembatan</span>
                        <h4 class="text-base font-extrabold text-[#0f2a71]">${item.namajbt}</h4>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nomor Jembatan</span>
                                <span class="font-mono font-bold text-slate-800">${item.nojbt}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Panjang Bentang</span>
                                <span class="font-mono font-extrabold text-emerald-700">${item.panjang.toFixed(2)} Meter</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Ruas Jalan</span>
                        <span class="font-bold text-slate-800 text-sm block">${item.ruas}</span>
                        <div class="mt-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Koridor Prioritas</span>
                            <span class="text-slate-600">${item.koridor || 'Tidak terkait koridor khusus'}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Kewenangan</span>
                            <span class="font-bold text-amber-700">${item.status}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Koordinat Geospasial</span>
                            <span class="font-mono text-slate-600 text-[11px]">${item.lat}, ${item.lng}</span>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('modalDetail').classList.remove('hidden');
        }

        function closeModalDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        // Export Functionality
        function exportCSV() {
            const data = getFilteredData();
            let csv = "No,Nama Ruas,Nama Koridor,Nomor Jembatan,Nama Jembatan,Panjang (M),Status,Latitude,Longitude\n";
            data.forEach((row, idx) => {
                csv += `"${idx + 1}","${row.ruas}","${row.koridor || ''}","${row.nojbt}","${row.namajbt}","${row.panjang}","${row.status}","${row.lat}","${row.lng}"\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", `Referensi_Jembatan_Jatim_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function exportExcel() {
            alert("Mempersiapkan berkas spreadsheet Excel Referensi Jembatan Jawa Timur...");
            exportCSV();
        }

        // Toggle Sidebar on mobile
        document.getElementById('toggleSidebarBtn').addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-ml-64');
        });

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            applyFilters();
        });
    </script>
</body>
</html>
