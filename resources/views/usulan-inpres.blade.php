@extends('layouts.app')

@section('title', 'Usulan Inpres - E-Jalin Dinas PU Bina Marga Provinsi Jawa Timur')

@push('styles')
    <!-- Leaflet for interactive map modal -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
            <!-- Breadcrumbs -->
            <nav class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 mb-6 gap-2">
                <a href="{{ url('/dashboard') }}" class="text-[#0f2a71] hover:underline">USULAN PEMDA</a>
                <span class="text-slate-400">&gt;</span>
                <span class="text-slate-600 font-extrabold">USULAN INPRES</span>
            </nav>

            <!-- Top Action Header & Buttons -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <button onclick="openModalTambah()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition transform hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>+ Tambah Usulan</span>
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

            <!-- Kapasitas Fiskal Daerah Banner (Exact SiTIA rule) -->
            <div class="mb-6 p-4 rounded-xl border border-amber-300 bg-amber-50/80 text-amber-900 text-xs sm:text-sm font-medium flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-lg bg-amber-200 text-amber-800 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    Berdasarkan <span class="font-bold">PMK No 97 Tahun 2025</span> (tanggal 22 Desember 2025), Rentang Kapasitas Fiskal Daerah (<span class="font-bold text-[#0f2a71]">JAWA TIMUR</span>) Adalah: 
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-extrabold ml-1 border border-emerald-300 uppercase text-xs">
                        Tinggi
                    </span>
                </div>
            </div>

            <!-- Filter Panel Box -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs mb-6">
                <div class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Filter Parameter Usulan</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 text-xs">
                    
                    <!-- 1. Provinsi -->
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Provinsi</label>
                        <input type="text" value="JAWA TIMUR" disabled class="w-full bg-slate-100 border border-slate-300 rounded-lg px-3 py-2 text-slate-600 font-bold cursor-not-allowed">
                    </div>

                    <!-- 2. Kab/Kota -->
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Kab/Kota</label>
                        <select id="filterKabKota" onchange="applyFilters()" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-800 font-medium focus:outline-none focus:border-[#0f2a71]">
                            <option value="">-- Semua Kab/Kota --</option>
                            <option value="KABUPATEN BANGKALAN">KABUPATEN BANGKALAN</option>
                            <option value="KABUPATEN BANYUWANGI">KABUPATEN BANYUWANGI</option>
                            <option value="KABUPATEN BLITAR">KABUPATEN BLITAR</option>
                            <option value="KABUPATEN BOJONEGORO">KABUPATEN BOJONEGORO</option>
                            <option value="KABUPATEN BONDOWOSO">KABUPATEN BONDOWOSO</option>
                            <option value="KABUPATEN GRESIK">KABUPATEN GRESIK</option>
                            <option value="KABUPATEN JEMBER">KABUPATEN JEMBER</option>
                            <option value="KABUPATEN JOMBANG">KABUPATEN JOMBANG</option>
                            <option value="KABUPATEN KEDIRI">KABUPATEN KEDIRI</option>
                            <option value="KABUPATEN LAMONGAN">KABUPATEN LAMONGAN</option>
                            <option value="KABUPATEN LUMAJANG">KABUPATEN LUMAJANG</option>
                            <option value="KABUPATEN MADIUN">KABUPATEN MADIUN</option>
                            <option value="KABUPATEN MAGETAN">KABUPATEN MAGETAN</option>
                            <option value="KABUPATEN MALANG">KABUPATEN MALANG</option>
                            <option value="KABUPATEN MOJOKERTO">KABUPATEN MOJOKERTO</option>
                            <option value="KABUPATEN NGANJUK">KABUPATEN NGANJUK</option>
                            <option value="KABUPATEN NGAWI">KABUPATEN NGAWI</option>
                            <option value="KABUPATEN PACITAN">KABUPATEN PACITAN</option>
                            <option value="KABUPATEN PAMEKASAN">KABUPATEN PAMEKASAN</option>
                            <option value="KABUPATEN PASURUAN">KABUPATEN PASURUAN</option>
                            <option value="KABUPATEN PONOROGO">KABUPATEN PONOROGO</option>
                            <option value="KABUPATEN PROBOLINGGO">KABUPATEN PROBOLINGGO</option>
                            <option value="KABUPATEN SAMPANG">KABUPATEN SAMPANG</option>
                            <option value="KABUPATEN SIDOARJO">KABUPATEN SIDOARJO</option>
                            <option value="KABUPATEN SITUBONDO">KABUPATEN SITUBONDO</option>
                            <option value="KABUPATEN SUMENEP">KABUPATEN SUMENEP</option>
                            <option value="KABUPATEN TRENGGALEK">KABUPATEN TRENGGALEK</option>
                            <option value="KABUPATEN TUBAN">KABUPATEN TUBAN</option>
                            <option value="KABUPATEN TULUNGAGUNG">KABUPATEN TULUNGAGUNG</option>
                            <option value="KOTA BATU">KOTA BATU</option>
                            <option value="KOTA BLITAR">KOTA BLITAR</option>
                            <option value="KOTA KEDIRI">KOTA KEDIRI</option>
                            <option value="KOTA MADIUN">KOTA MADIUN</option>
                            <option value="KOTA MALANG">KOTA MALANG</option>
                            <option value="KOTA MOJOKERTO">KOTA MOJOKERTO</option>
                            <option value="KOTA PASURUAN">KOTA PASURUAN</option>
                            <option value="KOTA PROBOLINGGO">KOTA PROBOLINGGO</option>
                            <option value="KOTA SURABAYA">KOTA SURABAYA</option>
                        </select>
                    </div>

                    <!-- 3. Nama Pengusul -->
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Nama Pengusul</label>
                        <select id="filterPengusul" onchange="applyFilters()" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-800 font-medium focus:outline-none focus:border-[#0f2a71]">
                            <option value="">-- Semua Pengusul --</option>
                            <option value="Gubernur Provinsi Jawa Timur">Gubernur Provinsi Jawa Timur</option>
                            <option value="Bupati Bangkalan">Bupati Bangkalan</option>
                            <option value="Bupati Banyuwangi">Bupati Banyuwangi</option>
                            <option value="Bupati Blitar">Bupati Blitar</option>
                            <option value="Bupati Bojonegoro">Bupati Bojonegoro</option>
                            <option value="Bupati Bondowoso">Bupati Bondowoso</option>
                            <option value="Bupati Gresik">Bupati Gresik</option>
                            <option value="Bupati Jember">Bupati Jember</option>
                            <option value="Bupati Jombang">Bupati Jombang</option>
                            <option value="Bupati Kediri">Bupati Kediri</option>
                            <option value="Bupati Lamongan">Bupati Lamongan</option>
                            <option value="Bupati Lumajang">Bupati Lumajang</option>
                            <option value="Bupati Madiun">Bupati Madiun</option>
                            <option value="Bupati Magetan">Bupati Magetan</option>
                            <option value="Bupati Malang">Bupati Malang</option>
                            <option value="Bupati Mojokerto">Bupati Mojokerto</option>
                            <option value="Bupati Nganjuk">Bupati Nganjuk</option>
                            <option value="Bupati Ngawi">Bupati Ngawi</option>
                            <option value="Bupati Pacitan">Bupati Pacitan</option>
                            <option value="Bupati Pamekasan">Bupati Pamekasan</option>
                            <option value="Bupati Pasuruan">Bupati Pasuruan</option>
                            <option value="Bupati Ponorogo">Bupati Ponorogo</option>
                            <option value="Bupati Probolinggo">Bupati Probolinggo</option>
                            <option value="Bupati Sampang">Bupati Sampang</option>
                            <option value="Bupati Sidoarjo">Bupati Sidoarjo</option>
                            <option value="Bupati Sumenep">Bupati Sumenep</option>
                            <option value="Bupati Trenggalek">Bupati Trenggalek</option>
                            <option value="Bupati Tuban">Bupati Tuban</option>
                            <option value="Bupati Tulungagung">Bupati Tulungagung</option>
                            <option value="Bupati Situbondo">Bupati Situbondo</option>
                            <option value="Walikota Batu">Walikota Batu</option>
                            <option value="Walikota Blitar">Walikota Blitar</option>
                            <option value="Walikota Kediri">Walikota Kediri</option>
                            <option value="Walikota Madiun">Walikota Madiun</option>
                            <option value="Walikota Malang">Walikota Malang</option>
                            <option value="Walikota Mojokerto">Walikota Mojokerto</option>
                            <option value="Walikota Pasuruan">Walikota Pasuruan</option>
                            <option value="Walikota Probolinggo">Walikota Probolinggo</option>
                            <option value="Walikota Surabaya">Walikota Surabaya</option>
                        </select>
                    </div>

                    <!-- 4. Tematik -->
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Tematik Kawasan</label>
                        <select id="filterTematik" onchange="applyFilters()" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-800 font-medium focus:outline-none focus:border-[#0f2a71]">
                            <option value="">-- Semua Tematik --</option>
                            <option value="Swasembada Pangan - Pertanian">Swasembada Pangan - Pertanian</option>
                            <option value="Swasembada Pangan - Perkebunan">Swasembada Pangan - Perkebunan</option>
                            <option value="Swasembada Pangan - Perikanan">Swasembada Pangan - Perikanan</option>
                            <option value="Swasembada Pangan - Peternakan">Swasembada Pangan - Peternakan</option>
                            <option value="Swasembada Pangan - Kawasan Mendukung Program MBG">Swasembada Pangan - Kawasan Mendukung Program MBG</option>
                            <option value="Distribusi Energi - Perkebunan yang mendukung Energi Terbarukan">Distribusi Energi - Energi Terbarukan</option>
                            <option value="Distribusi Energi - Mendukung Distribusi BBM satu harga">Distribusi Energi - BBM Satu Harga</option>
                            <option value="Kawasan Produktif Lainnya - Kawasan Transmigrasi">Kawasan Produktif - Transmigrasi</option>
                            <option value="Kawasan Produktif Lainnya - Kawasan Industri Prioritas">Kawasan Industri Prioritas</option>
                            <option value="Kawasan Produktif Lainnya - Kawasan Strategis Daerah">Kawasan Strategis Daerah</option>
                            <option value="Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Nasional)">Konektivitas - Ke Jalan Nasional</option>
                            <option value="Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Provinsi)">Konektivitas - Ke Jalan Provinsi</option>
                            <option value="Konektivitas - Terhubung Ke Simpul Transportasi">Konektivitas - Simpul Transportasi</option>
                        </select>
                    </div>

                    <!-- 5. Prioritas -->
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Prioritas</label>
                        <select id="filterPrioritas" onchange="applyFilters()" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-800 font-medium focus:outline-none focus:border-[#0f2a71]">
                            <option value="">-- Semua Prioritas --</option>
                            <option value="1">Prioritas 1</option>
                            <option value="2">Prioritas 2</option>
                            <option value="3">Prioritas 3</option>
                            <option value="4">Prioritas 4</option>
                            <option value="5">Prioritas 5</option>
                            <option value="6">Prioritas 6</option>
                            <option value="7">Prioritas 7</option>
                            <option value="8">Prioritas 8</option>
                            <option value="9">Prioritas 9</option>
                            <option value="10">Prioritas 10</option>
                        </select>
                    </div>

                </div>

                <!-- Reset Filter Action -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span id="filterSummaryText" class="text-slate-500 font-medium">Menampilkan seluruh 287 usulan terdaftar di Jawa Timur.</span>
                    <button onclick="resetFilters()" class="text-rose-600 hover:text-rose-700 font-bold flex items-center gap-1 hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Reset Filter</span>
                    </button>
                </div>
            </div>

            <!-- 4 Summary Metric Cards (Exact SiTIA values & E-Jalin Palette) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                
                <!-- Card 1: Jumlah Usulan -->
                <div class="bg-white p-5 rounded-2xl border-l-4 border-l-[#0f2a71] border border-slate-200 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Jumlah Usulan</div>
                        <div class="w-8 h-8 rounded-lg bg-[#0f2a71]/10 text-[#0f2a71] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="statJumlahUsulan" class="text-3xl font-black text-[#0f2a71] mt-3">287</div>
                    <div class="text-[11px] text-slate-400 mt-1 font-medium">Usulan PEMDA Jatim Terverifikasi</div>
                </div>

                <!-- Card 2: Total Indikasi Awal Jalan -->
                <div class="bg-white p-5 rounded-2xl border-l-4 border-l-rose-500 border border-slate-200 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Indikasi Awal Jalan</div>
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="statTotalJalan" class="text-3xl font-black text-rose-600 mt-3">931,63 <span class="text-base font-bold text-slate-500">KM</span></div>
                    <div class="text-[11px] text-slate-400 mt-1 font-medium">Ruas Jalan Strategis Provinsi & Daerah</div>
                </div>

                <!-- Card 3: Total Indikasi Awal Jembatan -->
                <div class="bg-white p-5 rounded-2xl border-l-4 border-l-[#ffb700] border border-slate-200 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Indikasi Awal Jembatan</div>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="statTotalJembatan" class="text-3xl font-black text-[#0f2a71] mt-3">1.808 <span class="text-base font-bold text-slate-500">M</span></div>
                    <div class="text-[11px] text-slate-400 mt-1 font-medium">Jembatan Penghubung Lintas Wilayah</div>
                </div>

                <!-- Card 4: Estimasi Kebutuhan Biaya -->
                <div class="bg-white p-5 rounded-2xl border-l-4 border-l-emerald-500 border border-slate-200 shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Estimasi Kebutuhan Biaya</div>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="statEstimasiBiaya" class="text-xl sm:text-2xl font-black text-emerald-700 mt-3 truncate" title="Rp 5.809.223.401.604">Rp 5.809.223.401.604</div>
                    <div class="text-[11px] text-slate-400 mt-1 font-medium">Total Anggaran Inpres Jawa Timur</div>
                </div>

            </div>

            <!-- Table Container Card with Dual Headers -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                
                <!-- Table Controls: Length & Live Search -->
                <div class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-slate-600 font-medium w-full sm:w-auto">
                        <select id="entriesPerPage" onchange="applyFilters()" class="border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-slate-700 bg-white focus:outline-none">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span>data per halaman</span>
                    </div>

                    <div class="relative w-full sm:w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchInput" onkeyup="applyFilters()" placeholder="Cari ruas, no usulan, kab/kota..." class="w-full pl-9 pr-3 py-1.5 text-xs border border-slate-300 rounded-lg focus:outline-none focus:border-[#0f2a71] focus:ring-1 focus:ring-[#0f2a71]">
                    </div>
                </div>

                <!-- Main Scrollable Table with SiTIA Exact Columns -->
                <div class="overflow-x-auto custom-scrollbar" style="max-height: 700px;">
                    <table id="table_usulan" class="w-full text-left border-collapse text-[11px] whitespace-nowrap">
                        
                        <!-- Dual-Row Sticky Table Header (E-Jalin Navy #0f2a71 & Gold Highlights) -->
                        <thead>
                            <tr class="bg-[#0f2a71] text-white divide-x divide-[#153894] border-b border-[#ffb700]">
                                <th rowspan="2" class="p-3 text-center font-bold tracking-wider w-10">No.</th>
                                <th rowspan="2" class="p-3 text-center font-bold tracking-wider">ID Usulan</th>
                                <th rowspan="2" class="p-3 text-left font-bold tracking-wider">Lokasi</th>
                                <th rowspan="2" class="p-3 text-left font-bold tracking-wider">Nama Pengusul</th>
                                <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[220px]">Nama Kegiatan</th>
                                <th rowspan="2" class="p-3 text-center font-bold tracking-wider min-w-[140px]">Dukungan K/L / DPR RI</th>
                                <th colspan="3" class="p-2.5 text-center font-bold tracking-wider bg-[#0a1e52] text-[#ffb700] border-b border-[#153894]">Prioritas</th>
                                <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[180px]">Nama Koridor</th>
                                <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[200px]">Nama Ruas</th>
                                <th rowspan="2" class="p-3 text-center font-bold tracking-wider">Status Ruas</th>
                                <th rowspan="2" class="p-3 text-left font-bold tracking-wider">Nama Jembatan</th>
                                <th rowspan="2" class="p-3 text-right font-bold tracking-wider">Panjang Penanganan</th>
                                <th rowspan="2" class="p-3 text-center font-bold tracking-wider">Satuan</th>
                                <th rowspan="2" class="p-3 text-right font-bold tracking-wider">Alokasi Usulan (Rp)</th>
                                <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[160px]">Tematik Kawasan</th>
                                <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[200px]">Keterangan</th>
                                <th colspan="3" class="p-2.5 text-center font-bold tracking-wider bg-[#0a1e52] text-[#ffb700] border-b border-[#153894]">Indikasi Ruas Prioritas</th>
                                <th colspan="3" class="p-2.5 text-center font-bold tracking-wider bg-[#0a1e52] text-[#ffb700] border-b border-[#153894]">Verifikasi</th>
                                <th colspan="2" class="p-2.5 text-center font-bold tracking-wider bg-[#0a1e52] text-[#ffb700] border-b border-[#153894]">Bilateral</th>
                                <th rowspan="2" class="p-3 text-center font-bold tracking-wider w-16">Peta</th>
                                <th rowspan="2" class="p-3 text-center font-bold tracking-wider w-24">Action</th>
                            </tr>
                            <tr class="bg-[#0b2158] text-[10px] text-slate-200 divide-x divide-[#153894]">
                                <!-- Prioritas sub-columns -->
                                <th class="p-2 text-center font-semibold">Pemda</th>
                                <th class="p-2 text-center font-semibold">Balai</th>
                                <th class="p-2 text-center font-semibold">Kompetensi</th>
                                <!-- Indikasi Ruas Prioritas sub-columns -->
                                <th class="p-2 text-center font-semibold">PU</th>
                                <th class="p-2 text-center font-semibold">Bappenas</th>
                                <th class="p-2 text-center font-semibold">Kemenko IPK</th>
                                <!-- Verifikasi sub-columns -->
                                <th class="p-2 text-center font-semibold">Balai</th>
                                <th class="p-2 text-center font-semibold">Kompetensi</th>
                                <th class="p-2 text-center font-semibold">PFID</th>
                                <!-- Bilateral sub-columns -->
                                <th class="p-2 text-center font-semibold">Bappenas</th>
                                <th class="p-2 text-center font-semibold">PU</th>
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
                        Menampilkan 1 s/d 10 dari 287 data
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
    <!-- MODAL: DETAIL / TINJAU USULAN (E-Jalin Style)                   -->
    <!-- ============================================================== -->
    <div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#ffb700] text-[#0f2a71] flex items-center justify-center font-black text-sm">
                        EJ
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Detail Tinjau Usulan Inpres</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Data Terverifikasi SiTIA Bina Marga Jawa Timur</p>
                    </div>
                </div>
                <button onclick="closeModalDetail()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content (Detail View) -->
            <div class="p-6 overflow-y-auto space-y-6 text-xs text-slate-700 flex-grow" id="modalDetailBody">
                <!-- Dynamically populated -->
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <button onclick="closeModalDetail()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: TAMBAH USULAN BARU (SiTIA Comprehensive Form)             -->
    <!-- ============================================================== -->
    <div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden">
            
            <!-- Header -->
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-black text-sm">
                        +
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Formulir Tambah Usulan Inpres Baru</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Pengusulan Penanganan Jalan &amp; Jembatan Daerah Provinsi Jawa Timur</p>
                    </div>
                </div>
                <button onclick="closeModalTambah()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <form id="frmTambahUsulan" onsubmit="handleFormSubmit(event)" class="p-6 overflow-y-auto space-y-6 text-xs text-slate-700 flex-grow">
                
                <!-- Notice Alert -->
                <div class="p-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-900">
                    <div class="font-bold mb-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Petunjuk Pengisian:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-amber-800">
                        <li>Desimal dipisah dengan "." (titik), contoh: <b>7.55</b>. Ribuan dipisah dengan koma / otomatis.</li>
                        <li>Format tanggal adalah <b>yyyy-mm-dd</b>. Semua kolom bertanda (<span class="text-red-500 font-bold">*</span>) wajib diisi.</li>
                        <li>Tombol <b>"Simpan Draft"</b> menyimpan usulan dengan status draft yang masih dapat diubah.</li>
                        <li>Tombol <b>"Submit Usulan"</b> menyimpan usulan final untuk diverifikasi oleh Dinas PU Bina Marga &amp; Balai.</li>
                    </ul>
                </div>

                <!-- Bagian 1: Identitas Usulan & Surat Resmi -->
                <div class="space-y-4">
                    <h4 class="font-extrabold text-[#0f2a71] text-xs uppercase tracking-wider pb-1 border-b border-slate-200 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#0f2a71] text-[#ffb700] inline-flex items-center justify-center text-[10px]">1</span>
                        <span>Identitas Usulan &amp; Surat Resmi</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
                            <input type="text" id="f_kegiatan" required placeholder="Contoh: Preservasi Jalan Ruas Bts. Kota Lumajang - Bts.Kab Jember" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">No Surat Usulan <span class="text-red-500">*</span></label>
                            <input type="text" id="f_nosurat" required placeholder="Contoh: 600.1.7/1042/103.4/2026" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                            <input type="date" id="f_tglsurat" required value="2026-11-09" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Perihal Surat <span class="text-red-500">*</span></label>
                            <input type="text" id="f_perihal" required placeholder="Usulan Penanganan Jalan Daerah Tahun 2026" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Prioritas Pemda (1-20) <span class="text-red-500">*</span></label>
                            <select id="f_prioritas" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="1">Prioritas 1 (Utama)</option>
                                <option value="2">Prioritas 2</option>
                                <option value="3">Prioritas 3</option>
                                <option value="4">Prioritas 4</option>
                                <option value="5">Prioritas 5</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Upload Surat Resmi (PDF) <span class="text-red-500">*</span></label>
                            <input type="file" accept=".pdf" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-[#0f2a71] file:text-white">
                        </div>
                    </div>
                </div>

                <!-- Bagian 2: Dukungan Lembaga / DPR RI -->
                <div class="space-y-4">
                    <h4 class="font-extrabold text-[#0f2a71] text-xs uppercase tracking-wider pb-1 border-b border-slate-200 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#0f2a71] text-[#ffb700] inline-flex items-center justify-center text-[10px]">2</span>
                        <span>Dukungan Kementerian / Lembaga / DPR RI</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Status Dukungan</label>
                            <select id="f_dukungan_tipe" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="ADA">KEMENTERIAN / LEMBAGA / DPR RI</option>
                                <option value="TIDAK ADA" selected>TIDAK ADA DUKUNGAN</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Pemberi Dukungan (Komisi V DPR RI)</label>
                            <select id="f_dpr_nama" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="">-- Pilih Anggota Komisi V (Jika ada) --</option>
                                <option value="H. Sudjatmiko, S.T. (JATIM VIII)">KOMISI V - H. Sudjatmiko, S.T. - JATIM VIII</option>
                                <option value="Ir. Sumail Abdullah (JATIM III)">KOMISI V - Ir. Sumail Abdullah - JATIM III</option>
                                <option value="Arizal Tom Liwafa, S.T. (JATIM I)">KOMISI V - Arizal Tom Liwafa, S.T. - JATIM I</option>
                                <option value="Dr. H. Ali Mufthi, S.Ag. (JATIM VII)">KOMISI V - Dr. H. Ali Mufthi, S.Ag. - JATIM VII</option>
                                <option value="Drs. Supriyanto (JATIM VII)">KOMISI V - Drs. Supriyanto - JATIM VII</option>
                                <option value="H. Syafiuddin, S.Sos. (JATIM XI)">KOMISI V - H. Syafiuddin, S.Sos. - JATIM XI</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Bagian 3: Rincian Ruas / Jembatan & Spesifikasi Teknis -->
                <div class="space-y-4">
                    <h4 class="font-extrabold text-[#0f2a71] text-xs uppercase tracking-wider pb-1 border-b border-slate-200 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#0f2a71] text-[#ffb700] inline-flex items-center justify-center text-[10px]">3</span>
                        <span>Ruas / Jembatan &amp; Spesifikasi Teknis</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tematik Kawasan <span class="text-red-500">*</span></label>
                            <select id="f_tematik" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="Swasembada Pangan - Pertanian">Swasembada Pangan - Pertanian</option>
                                <option value="Swasembada Pangan - Perkebunan">Swasembada Pangan - Perkebunan</option>
                                <option value="Swasembada Pangan - Perikanan">Swasembada Pangan - Perikanan</option>
                                <option value="Swasembada Pangan - Peternakan">Swasembada Pangan - Peternakan</option>
                                <option value="Swasembada Pangan - Kawasan Mendukung Program MBG">Swasembada Pangan - Mendukung Program MBG</option>
                                <option value="Distribusi Energi - Pekerbunan yang mendukung Energi Terbarukan">Distribusi Energi - Energi Terbarukan</option>
                                <option value="Distribusi Energi - Mendukung Distribusi BBM satu harga">Distribusi Energi - Distribusi BBM Satu Harga</option>
                                <option value="Kawasan Produktif Lainnya - Kawasan Industri Prioritas">Kawasan Industri Prioritas</option>
                                <option value="Kawasan Produktif Lainnya - Kawasan Strategis Daerah">Kawasan Strategis Daerah</option>
                                <option value="Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Nasional)">Konektivitas - Ke Jalan Nasional</option>
                                <option value="Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Provinsi)">Konektivitas - Ke Jalan Provinsi</option>
                                <option value="Konektivitas - Terhubung Ke Simpul Transportasi">Konektivitas - Ke Simpul Transportasi</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jenis Penanganan <span class="text-red-500">*</span></label>
                            <select id="f_jenis_penanganan" onchange="toggleBridgeField()" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="Perbaikan Jalan">Perbaikan Jalan</option>
                                <option value="Pelebaran Jalan">Pelebaran Jalan</option>
                                <option value="Peningkatan Jalan">Peningkatan Jalan</option>
                                <option value="Pembangunan Jalan Baru">Pembangunan Jalan Baru</option>
                                <option value="Perbaikan Jembatan">Perbaikan Jembatan</option>
                                <option value="Pembangunan Jembatan Baru">Pembangunan Jembatan Baru</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <select id="f_kabkota" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="KABUPATEN LUMAJANG">KABUPATEN LUMAJANG</option>
                                <option value="KABUPATEN JOMBANG">KABUPATEN JOMBANG</option>
                                <option value="KABUPATEN SUMENEP">KABUPATEN SUMENEP</option>
                                <option value="KABUPATEN MALANG">KABUPATEN MALANG</option>
                                <option value="KABUPATEN BANYUWANGI">KABUPATEN BANYUWANGI</option>
                                <option value="KABUPATEN PASURUAN">KABUPATEN PASURUAN</option>
                                <option value="KABUPATEN TULUNGAGUNG">KABUPATEN TULUNGAGUNG</option>
                                <option value="KABUPATEN BOJONEGORO">KABUPATEN BOJONEGORO</option>
                                <option value="KABUPATEN TUBAN">KABUPATEN TUBAN</option>
                                <option value="KABUPATEN BANGKALAN">KABUPATEN BANGKALAN</option>
                                <option value="KOTA SURABAYA">KOTA SURABAYA</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Pengusul <span class="text-red-500">*</span></label>
                            <input type="text" id="f_pengusul" required value="Gubernur Provinsi Jawa Timur" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Koridor <span class="text-red-500">*</span></label>
                            <input type="text" id="f_koridor" required placeholder="Contoh: Pabrik Gula Jatiroto-Yosowilangun-Kota Lumajang" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Ruas (Referensi) <span class="text-red-500">*</span></label>
                            <input type="text" id="f_ruas" required placeholder="Contoh: BTS. KOTA LUMAJANG - BTS. KAB. JEMBER" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div id="fieldJembatanWrap" class="hidden">
                            <label class="block font-bold text-slate-700 mb-1">Nama Jembatan <span class="text-red-500">*</span></label>
                            <input type="text" id="f_jembatan" placeholder="Nama jembatan penghubung" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Status Ruas <span class="text-red-500">*</span></label>
                            <select id="f_statusruas" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="Provinsi">Provinsi</option>
                                <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Panjang Penanganan <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-2">
                                <input type="number" step="0.01" id="f_panjang" required placeholder="Contoh: 3.50" class="flex-grow border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <select id="f_satuan" class="w-24 border border-slate-300 rounded-lg px-3 py-2 font-bold bg-slate-50">
                                    <option value="KM" selected>KM</option>
                                    <option value="M">M</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Alokasi Biaya Usulan (Rp) <span class="text-red-500">*</span></label>
                            <input type="text" id="f_biaya" required placeholder="Contoh: 25.000.000.000" class="w-full border border-slate-300 rounded-lg px-3 py-2 font-bold text-[#0f2a71] focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Keterangan / Justifikasi Usulan</label>
                            <textarea id="f_keterangan" rows="2" placeholder="Catatan dukungan kawasan pangan, industri, konektivitas, dll." class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Bagian 4: Kesiapan Dokumen RC (Readiness Criteria) -->
                <div class="space-y-4">
                    <h4 class="font-extrabold text-[#0f2a71] text-xs uppercase tracking-wider pb-1 border-b border-slate-200 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#0f2a71] text-[#ffb700] inline-flex items-center justify-center text-[10px]">4</span>
                        <span>Dokumen Readiness Criteria (RC)</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <label class="block font-bold text-slate-700 mb-1">RC DED (Detail Engineering Design) <span class="text-red-500">*</span></label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-1.5 mb-2 focus:outline-none">
                                <option value="SIAP">SIAP</option>
                                <option value="TIDAK SIAP">TIDAK SIAP</option>
                                <option value="TIDAK PERLU">TIDAK PERLU</option>
                            </select>
                            <input type="file" accept=".pdf" class="w-full text-[11px] text-slate-500">
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <label class="block font-bold text-slate-700 mb-1">RC FS (Feasibility Study) <span class="text-red-500">*</span></label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-1.5 mb-2 focus:outline-none">
                                <option value="SIAP">SIAP</option>
                                <option value="TIDAK SIAP">TIDAK SIAP</option>
                                <option value="TIDAK PERLU">TIDAK PERLU</option>
                            </select>
                            <input type="file" accept=".pdf" class="w-full text-[11px] text-slate-500">
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <label class="block font-bold text-slate-700 mb-1">RC Kesiapan Lahan <span class="text-red-500">*</span></label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-1.5 mb-2 focus:outline-none">
                                <option value="SIAP">SIAP</option>
                                <option value="TIDAK SIAP">TIDAK SIAP</option>
                                <option value="TIDAK PERLU">TIDAK PERLU</option>
                            </select>
                            <input type="file" accept=".pdf" class="w-full text-[11px] text-slate-500">
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <label class="block font-bold text-slate-700 mb-1">RC Dokumen Lingkungan <span class="text-red-500">*</span></label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-1.5 mb-2 focus:outline-none">
                                <option value="SIAP">SIAP</option>
                                <option value="TIDAK SIAP">TIDAK SIAP</option>
                                <option value="TIDAK PERLU">TIDAK PERLU</option>
                            </select>
                            <input type="file" accept=".pdf" class="w-full text-[11px] text-slate-500">
                        </div>
                    </div>
                </div>

                <!-- Action Submit Buttons -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModalTambah()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-sm transition">
                        Simpan Usulan Inpres
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: PETA SPASIAL GEOMETRI RUAS (Leaflet GIS)                 -->
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
                        <h3 id="petaTitle" class="text-base font-bold text-white leading-tight">Peta Spasial Geometri Ruas</h3>
                        <p id="petaSubtitle" class="text-[10px] text-slate-300 font-medium">Jalur Geospasial Usulan Inpres Jatim</p>
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
                <span>Koordinat Geografis Jaringan Jalan Dinas PU Bina Marga Provinsi Jawa Timur</span>
                <button onclick="closeModalPeta()" class="px-4 py-1.5 bg-[#0f2a71] text-white rounded-lg font-bold hover:bg-navy-800 transition">
                    Tutup Peta
                </button>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Script: Data, Filtering, Pagination, and Modals -->
    <script>
        // Sample realistic East Java dataset derived from SiTIA HTML source
        const defaultUsulanData = [
            {
                id: "238898",
                lokasi: "JAWA TIMUR - KABUPATEN LUMAJANG",
                kabkota: "KABUPATEN LUMAJANG",
                pengusul: "Gubernur Provinsi Jawa Timur",
                kegiatan: "Preservasi Jalan Ruas Bts. Kota Lumajang - Bts.Kab Jember",
                dukunganDPR: "TIDAK ADA",
                prioritasPemda: 1,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "Pabrik Gula Jatiroto-Yosowilangun-Kota Lumajang (36,48 KM)",
                ruas: "BTS. KOTA LUMAJANG - BTS. KAB. JEMBER (14,18 KM)",
                statusRuas: "Provinsi",
                jembatan: "",
                panjang: "2,50",
                satuan: "KM",
                biaya: 21303604000,
                tematik: "Swasembada Pangan - Perkebunan",
                keterangan: "Mendukung tematik kawasan komoditas unggulan tebu tapal kuda Kab. Lumajang, Koridor Pabrik Gula Jatiroto –Yosowilangun.",
                lat: -8.1331,
                lng: 113.2223,
                verifPU: true,
                verifBappenas: false,
                verifKemenko: true
            },
            {
                id: "238919",
                lokasi: "JAWA TIMUR - KABUPATEN JOMBANG",
                kabkota: "KABUPATEN JOMBANG",
                pengusul: "Bupati Jombang",
                kegiatan: "Rehabilitasi Jalan Bts Kota Jombang - Bts Kab Mojokerto",
                dukunganDPR: "H. Sudjatmiko, S.T.",
                prioritasPemda: 1,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "---",
                ruas: "BTS KOTA JOMBANG - BTS KAB MOJOKERTO (4,77 KM)",
                statusRuas: "Kabupaten/Kota",
                jembatan: "",
                panjang: "3,40",
                satuan: "KM",
                biaya: 45400000000,
                tematik: "Swasembada Pangan - Pertanian",
                keterangan: "Menjadi akses utama yang mendukung kawasan Swasembada Pangan di Kabupaten Jombang.",
                lat: -7.5468,
                lng: 112.2331,
                verifPU: true,
                verifBappenas: true,
                verifKemenko: true
            },
            {
                id: "238947",
                lokasi: "JAWA TIMUR - KABUPATEN SUMENEP",
                kabkota: "KABUPATEN SUMENEP",
                pengusul: "Bupati Sumenep",
                kegiatan: "Preservasi Jalan Matanair - Rubaru (No. 106)",
                dukunganDPR: "TIDAK ADA",
                prioritasPemda: 1,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "---",
                ruas: "MATANAIR - RUBARU (NO. 106) (7,97 KM)",
                statusRuas: "Kabupaten/Kota",
                jembatan: "",
                panjang: "1,68",
                satuan: "KM",
                biaya: 12215400000,
                tematik: "Swasembada Pangan - Pertanian",
                keterangan: "Akses sentra produksi bawang merah dan holtikultura Madura Timur.",
                lat: -6.9934,
                lng: 113.8427,
                verifPU: true,
                verifBappenas: false,
                verifKemenko: true
            },
            {
                id: "238962",
                lokasi: "JAWA TIMUR - KABUPATEN BANYUWANGI",
                kabkota: "KABUPATEN BANYUWANGI",
                pengusul: "Bupati Banyuwangi",
                kegiatan: "Pelebaran Jalan Ruas Rogojampi - Songgon",
                dukunganDPR: "Ir. Sumail Abdullah",
                prioritasPemda: 2,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "Kawasan Agrowisata & Sentra Durian Songgon (24,10 KM)",
                ruas: "ROGOJAMPI - SONGGON (12,50 KM)",
                statusRuas: "Kabupaten/Kota",
                jembatan: "",
                panjang: "4,20",
                satuan: "KM",
                biaya: 32850000000,
                tematik: "Kawasan Produktif Lainnya - Kawasan Industri Prioritas",
                keterangan: "Mendukung konektivitas kawasan sentra logistik dan pariwisata Banyuwangi Barat.",
                lat: -8.3032,
                lng: 114.2965,
                verifPU: true,
                verifBappenas: true,
                verifKemenko: true
            },
            {
                id: "239011",
                lokasi: "JAWA TIMUR - KABUPATEN MALANG",
                kabkota: "KABUPATEN MALANG",
                pengusul: "Bupati Malang",
                kegiatan: "Rekonstruksi Jalan Ruas Gondanglegi - Balekambang",
                dukunganDPR: "Arizal Tom Liwafa, S.T.",
                prioritasPemda: 1,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "Kawasan Koridor Selatan Pansela Jawa Timur (31,00 KM)",
                ruas: "GONDANGLEGI - BALEKAMBANG (31,00 KM)",
                statusRuas: "Provinsi",
                jembatan: "",
                panjang: "5,60",
                satuan: "KM",
                biaya: 68200000000,
                tematik: "Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Provinsi)",
                keterangan: "Akses vital menuju Jalur Lintas Selatan (JLS) dan kawasan sentra perikanan Sendangbiru.",
                lat: -8.2811,
                lng: 112.5510,
                verifPU: true,
                verifBappenas: true,
                verifKemenko: true
            },
            {
                id: "239055",
                lokasi: "JAWA TIMUR - KABUPATEN PASURUAN",
                kabkota: "KABUPATEN PASURUAN",
                pengusul: "Gubernur Provinsi Jawa Timur",
                kegiatan: "Peningkatan Ruas Kejayan - Tosari (Akses Wisata Bromo)",
                dukunganDPR: "TIDAK ADA",
                prioritasPemda: 1,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "Akses Pariwisata Strategis Nasional Bromo-Tengger-Semeru (BTS)",
                ruas: "KEJAYAN - TOSARI (38,20 KM)",
                statusRuas: "Provinsi",
                jembatan: "",
                panjang: "3,10",
                satuan: "KM",
                biaya: 28750000000,
                tematik: "Kawasan Produktif Lainnya - Kawasan Strategis Daerah",
                keterangan: "Mendukung pengembangan KSPN Bromo-Tengger-Semeru dari koridor Pasuruan.",
                lat: -7.8682,
                lng: 112.9125,
                verifPU: true,
                verifBappenas: false,
                verifKemenko: true
            },
            {
                id: "239108",
                lokasi: "JAWA TIMUR - KABUPATEN TULUNGAGUNG",
                kabkota: "KABUPATEN TULUNGAGUNG",
                pengusul: "Bupati Tulungagung",
                kegiatan: "Preservasi Ruas Jalan Kresikan - Dawung",
                dukunganDPR: "Dr. H. Ali Mufthi, S.Ag.",
                prioritasPemda: 2,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "Kawasan Agropolitan Tanggunggunung",
                ruas: "RUAS JALAN KRESIKAN - DAWUNG (10,40 KM)",
                statusRuas: "Kabupaten/Kota",
                jembatan: "",
                panjang: "2,80",
                satuan: "KM",
                biaya: 18500000000,
                tematik: "Swasembada Pangan - Pertanian",
                keterangan: "Mendukung distribusi jagung dan komoditas palawija kawasan Tulungagung Selatan.",
                lat: -8.1402,
                lng: 111.9103,
                verifPU: true,
                verifBappenas: true,
                verifKemenko: false
            },
            {
                id: "239145",
                lokasi: "JAWA TIMUR - KABUPATEN BOJONEGORO",
                kabkota: "KABUPATEN BOJONEGORO",
                pengusul: "Bupati Bojonegoro",
                kegiatan: "Pembangunan Jembatan Kanor - Rengel (Kare)",
                dukunganDPR: "TIDAK ADA",
                prioritasPemda: 1,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "Konektivitas Lintas Bengawan Solo Bojonegoro - Tuban",
                ruas: "KANOR - RENGEL (3,20 KM)",
                statusRuas: "Kabupaten/Kota",
                jembatan: "Jembatan Kare Bengawan Solo",
                panjang: "120,00",
                satuan: "M",
                biaya: 54000000000,
                tematik: "Konektivitas - Terhubung Ke Simpul Transportasi",
                keterangan: "Menghubungkan pusat perekonomian pedesaan Bojonegoro Timur dengan Tuban Selatan.",
                lat: -7.1124,
                lng: 112.0019,
                verifPU: true,
                verifBappenas: true,
                verifKemenko: true
            },
            {
                id: "239201",
                lokasi: "JAWA TIMUR - KABUPATEN TUBAN",
                kabkota: "KABUPATEN TUBAN",
                pengusul: "Bupati Tuban",
                kegiatan: "Pelebaran Jalan Ruas Merakurak - Jenu",
                dukunganDPR: "TIDAK ADA",
                prioritasPemda: 3,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "Kawasan Industri Kilang Minyak & Pelabuhan Tuban",
                ruas: "MERAKURAK - JENU (9,80 KM)",
                statusRuas: "Kabupaten/Kota",
                jembatan: "",
                panjang: "3,90",
                satuan: "KM",
                biaya: 29400000000,
                tematik: "Distribusi Energi - Mendukung Distribusi BBM satu harga",
                keterangan: "Akses penunjang arus logistik industri minyak dan gas di pesisir Pantura Tuban.",
                lat: -6.8712,
                lng: 111.9841,
                verifPU: true,
                verifBappenas: false,
                verifKemenko: true
            },
            {
                id: "239240",
                lokasi: "JAWA TIMUR - KABUPATEN BANGKALAN",
                kabkota: "KABUPATEN BANGKALAN",
                pengusul: "Bupati Bangkalan",
                kegiatan: "Preservasi Ruas Jalan Kamal - Kwanyar (R.51)",
                dukunganDPR: "H. Syafiuddin, S.Sos.",
                prioritasPemda: 1,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: "Konektivitas Pelabuhan Kamal - Akses Suramadu",
                ruas: "KAMAL - KWANYAR (R.51) (15,20 KM)",
                statusRuas: "Kabupaten/Kota",
                jembatan: "",
                panjang: "4,50",
                satuan: "KM",
                biaya: 24100000000,
                tematik: "Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Nasional)",
                keterangan: "Akses utama lingkar selatan Madura yang menghubungkan Pelabuhan Kamal dengan Jembatan Suramadu.",
                lat: -7.1654,
                lng: 112.7210,
                verifPU: true,
                verifBappenas: true,
                verifKemenko: true
            }
        ];

        let usulanList = [...defaultUsulanData];
        let currentPage = 1;
        let leafletMap = null;
        let leafletMarker = null;

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
        }

        // Render Table Rows
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
                        <td colspan="23" class="p-8 text-center text-slate-400 font-bold bg-slate-50">
                            Tidak ada data usulan inpres yang cocok dengan kriteria pencarian/filter.
                        </td>
                    </tr>
                `;
                document.getElementById('paginationInfo').innerText = "Menampilkan 0 data";
                return;
            }

            pageData.forEach((row, idx) => {
                const globalIndex = start + idx + 1;
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50 transition border-b border-slate-200 divide-x divide-slate-100";

                const dprIcon = row.dukunganDPR && row.dukunganDPR !== 'TIDAK ADA' 
                    ? `<span class="inline-flex items-center gap-1 text-emerald-600 font-bold" title="${row.dukunganDPR}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        <span>${row.dukunganDPR.split(' ')[0]}</span>
                       </span>`
                    : `<span class="text-rose-500 font-bold text-base">&times;</span>`;

                tr.innerHTML = `
                    <td class="p-2.5 text-center font-semibold text-slate-500">${globalIndex}</td>
                    <td class="p-2.5 text-center font-mono font-bold text-[#0f2a71] bg-slate-50/50">${row.id}</td>
                    <td class="p-2.5 font-medium text-slate-800">${row.lokasi}</td>
                    <td class="p-2.5 text-slate-700">${row.pengusul}</td>
                    <td class="p-2.5 font-bold text-[#0f2a71] whitespace-normal min-w-[220px] leading-tight">${row.kegiatan}</td>
                    <td class="p-2.5 text-center">${dprIcon}</td>
                    
                    <!-- Prioritas (Pemda, Balai, Kompetensi) -->
                    <td class="p-2.5 text-center font-bold text-[#0f2a71] bg-amber-50/30">${row.prioritasPemda || '-'}</td>
                    <td class="p-2.5 text-center text-slate-400">${row.prioritasBalai || '-'}</td>
                    <td class="p-2.5 text-center text-slate-400">${row.prioritasKomp || '-'}</td>

                    <td class="p-2.5 text-slate-600 whitespace-normal min-w-[180px] leading-snug">${row.koridor}</td>
                    <td class="p-2.5 font-medium text-slate-800 whitespace-normal min-w-[200px] leading-snug">${row.ruas}</td>
                    <td class="p-2.5 text-center font-semibold ${row.statusRuas === 'Provinsi' ? 'text-amber-800 bg-amber-50 rounded' : 'text-blue-800 bg-blue-50 rounded'}">${row.statusRuas}</td>
                    <td class="p-2.5 text-slate-600">${row.jembatan || '---'}</td>
                    <td class="p-2.5 text-right font-mono font-bold text-slate-800">${row.panjang}</td>
                    <td class="p-2.5 text-center font-bold text-slate-500">${row.satuan}</td>
                    <td class="p-2.5 text-right font-mono font-bold text-emerald-700">${formatRupiah(row.biaya).replace(',00', '')}</td>
                    <td class="p-2.5 text-slate-700 whitespace-normal min-w-[160px] leading-snug">${row.tematik}</td>
                    <td class="p-2.5 text-slate-500 whitespace-normal min-w-[200px] max-w-xs truncate" title="${row.keterangan}">${row.keterangan || '-'}</td>

                    <!-- Indikasi Ruas Prioritas (PU, Bappenas, Kemenko IPK) -->
                    <td class="p-2.5 text-center"><span class="w-2.5 h-2.5 rounded-full inline-block ${row.verifPU ? 'bg-emerald-500' : 'bg-slate-300'}" title="${row.verifPU ? 'Prioritas PU' : 'Belum'}"></span></td>
                    <td class="p-2.5 text-center"><span class="w-2.5 h-2.5 rounded-full inline-block ${row.verifBappenas ? 'bg-emerald-500' : 'bg-slate-300'}" title="${row.verifBappenas ? 'Prioritas Bappenas' : 'Belum'}"></span></td>
                    <td class="p-2.5 text-center"><span class="w-2.5 h-2.5 rounded-full inline-block ${row.verifKemenko ? 'bg-emerald-500' : 'bg-slate-300'}" title="${row.verifKemenko ? 'Prioritas Kemenko' : 'Belum'}"></span></td>

                    <!-- Verifikasi (Balai, Kompetensi, PFID) -->
                    <td class="p-2.5 text-center text-emerald-600 font-bold">&#10003;</td>
                    <td class="p-2.5 text-center text-slate-400">&#9633;</td>
                    <td class="p-2.5 text-center text-emerald-600 font-bold">&#10003;</td>

                    <!-- Bilateral (Bappenas, PU) -->
                    <td class="p-2.5 text-center text-slate-400">&#9633;</td>
                    <td class="p-2.5 text-center text-slate-400">&#9633;</td>

                    <!-- Peta Pin -->
                    <td class="p-2.5 text-center">
                        <button onclick="openModalPeta('${row.id}')" title="Lihat Peta Spasial Geometri" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </td>

                    <!-- Action Buttons -->
                    <td class="p-2.5 text-center">
                        <div class="inline-flex items-center gap-1">
                            <button onclick="openModalDetail('${row.id}')" title="Tinjau Detail Usulan" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                            <button onclick="editUsulan('${row.id}')" title="Edit Usulan" class="p-1.5 rounded-lg bg-blue-50 text-[#0f2a71] hover:bg-blue-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button onclick="deleteUsulan('${row.id}')" title="Hapus Usulan" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                `;

                tbody.appendChild(tr);
            });

            document.getElementById('paginationInfo').innerText = `Menampilkan ${start + 1} s/d ${Math.min(end, dataToRender.length)} dari ${dataToRender.length} data`;
            document.getElementById('pageIndicator').innerText = `Halaman ${currentPage}`;
        }

        // Filter and Search Logic
        function getFilteredData() {
            const query = (document.getElementById('searchInput').value || '').toLowerCase().trim();
            const kabkota = document.getElementById('filterKabKota').value;
            const pengusul = document.getElementById('filterPengusul').value;
            const tematik = document.getElementById('filterTematik').value;
            const prioritas = document.getElementById('filterPrioritas').value;

            return usulanList.filter(item => {
                const matchQuery = !query || 
                    item.id.toLowerCase().includes(query) ||
                    item.kegiatan.toLowerCase().includes(query) ||
                    item.ruas.toLowerCase().includes(query) ||
                    item.lokasi.toLowerCase().includes(query) ||
                    (item.koridor && item.koridor.toLowerCase().includes(query));

                const matchKabKota = !kabkota || item.kabkota === kabkota;
                const matchPengusul = !pengusul || item.pengusul === pengusul;
                const matchTematik = !tematik || item.tematik.toLowerCase().includes(tematik.toLowerCase());
                const matchPrioritas = !prioritas || item.prioritasPemda.toString() === prioritas;

                return matchQuery && matchKabKota && matchPengusul && matchTematik && matchPrioritas;
            });
        }

        function applyFilters() {
            const filtered = getFilteredData();
            currentPage = 1;
            renderTable(filtered);
            updateSummaryCards(filtered);
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterKabKota').value = '';
            document.getElementById('filterPengusul').value = '';
            document.getElementById('filterTematik').value = '';
            document.getElementById('filterPrioritas').value = '';
            applyFilters();
        }

        function updateSummaryCards(filteredData) {
            document.getElementById('statJumlahUsulan').innerText = filteredData.length;
            
            let totalJalan = 0;
            let totalBiaya = 0;
            filteredData.forEach(item => {
                if (item.satuan === 'KM') {
                    totalJalan += parseFloat(item.panjang.replace(',', '.')) || 0;
                }
                totalBiaya += item.biaya || 0;
            });

            document.getElementById('statTotalJalan').innerHTML = `${totalJalan.toFixed(2).replace('.', ',')} <span class="text-base font-bold text-slate-500">KM</span>`;
            document.getElementById('statEstimasiBiaya').innerText = formatRupiah(totalBiaya).replace(',00', '');
            document.getElementById('filterSummaryText').innerText = `Menampilkan ${filteredData.length} usulan terpilih sesuai filter.`;
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

        // Export Functions
        function exportCSV() {
            const data = getFilteredData();
            let csv = "No,ID Usulan,Lokasi,Nama Pengusul,Nama Kegiatan,Panjang,Satuan,Alokasi Usulan,Tematik Kawasan\n";
            data.forEach((row, idx) => {
                csv += `"${idx + 1}","${row.id}","${row.lokasi}","${row.pengusul}","${row.kegiatan}","${row.panjang}","${row.satuan}","${row.biaya}","${row.tematik}"\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", `Usulan_Inpres_Jatim_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function exportExcel() {
            alert("Mempersiapkan dokumen spreadsheet Excel data Usulan Inpres Jawa Timur...");
            exportCSV();
        }

        // Modal: Detail Tinjau
        function openModalDetail(id) {
            const item = usulanList.find(x => x.id === id);
            if (!item) return;

            const modalBody = document.getElementById('modalDetailBody');
            modalBody.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">ID Usulan</span>
                        <span class="text-base font-mono font-black text-[#0f2a71]">${item.id}</span>
                        <div class="mt-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nama Kegiatan</span>
                            <span class="font-extrabold text-[#0f2a71] text-sm">${item.kegiatan}</span>
                        </div>
                        <div class="mt-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Lokasi & Pengusul</span>
                            <span class="font-semibold text-slate-700 block">${item.lokasi}</span>
                            <span class="text-slate-500 font-medium">${item.pengusul}</span>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Alokasi Anggaran Usulan</span>
                        <span class="text-xl font-mono font-black text-emerald-700">${formatRupiah(item.biaya).replace(',00', '')}</span>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Panjang Penanganan</span>
                                <span class="font-bold text-slate-800">${item.panjang} ${item.satuan}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Ruas</span>
                                <span class="font-bold text-amber-700">${item.statusRuas}</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Dukungan DPR RI / Lembaga</span>
                            <span class="font-bold ${item.dukunganDPR !== 'TIDAK ADA' ? 'text-emerald-700' : 'text-slate-400'}">${item.dukunganDPR || 'Tidak ada'}</span>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tematik Kawasan</span>
                            <span class="font-bold text-[#0f2a71]">${item.tematik}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nama Koridor & Ruas</span>
                            <span class="font-semibold text-slate-800 block">${item.koridor}</span>
                            <span class="text-slate-600">${item.ruas}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Keterangan / Justifikasi Teknis</span>
                            <p class="text-slate-600 text-xs leading-relaxed mt-0.5">${item.keterangan || 'Tidak ada keterangan tambahan.'}</p>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-4 rounded-xl border border-slate-200">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">Kelengkapan Dokumen Readiness Criteria (RC)</span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                            <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-200 text-emerald-800 font-bold">RC DED: SIAP</div>
                            <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-200 text-emerald-800 font-bold">RC FS: SIAP</div>
                            <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-200 text-emerald-800 font-bold">RC Lahan: SIAP</div>
                            <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-200 text-emerald-800 font-bold">RC Dokling: SIAP</div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('modalDetail').classList.remove('hidden');
        }

        function closeModalDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        // Modal: Tambah Usulan
        function openModalTambah() {
            document.getElementById('frmTambahUsulan').reset();
            document.getElementById('modalTambah').classList.remove('hidden');
        }

        function closeModalTambah() {
            document.getElementById('modalTambah').classList.add('hidden');
        }

        function toggleBridgeField() {
            const jenis = document.getElementById('f_jenis_penanganan').value;
            const wrap = document.getElementById('fieldJembatanWrap');
            if (jenis.includes('Jembatan')) {
                wrap.classList.remove('hidden');
                document.getElementById('f_satuan').value = 'M';
            } else {
                wrap.classList.add('hidden');
                document.getElementById('f_satuan').value = 'KM';
            }
        }

        function handleFormSubmit(e) {
            e.preventDefault();
            const newId = (Math.floor(239000 + Math.random() * 9000)).toString();
            const kab = document.getElementById('f_kabkota').value;
            const newUsulan = {
                id: newId,
                lokasi: `JAWA TIMUR - ${kab}`,
                kabkota: kab,
                pengusul: document.getElementById('f_pengusul').value,
                kegiatan: document.getElementById('f_kegiatan').value,
                dukunganDPR: document.getElementById('f_dukungan_tipe').value === 'ADA' ? (document.getElementById('f_dpr_nama').value || 'Ada') : 'TIDAK ADA',
                prioritasPemda: parseInt(document.getElementById('f_prioritas').value) || 1,
                prioritasBalai: "",
                prioritasKomp: "",
                koridor: document.getElementById('f_koridor').value,
                ruas: document.getElementById('f_ruas').value,
                statusRuas: document.getElementById('f_statusruas').value,
                jembatan: document.getElementById('f_jembatan').value || '',
                panjang: document.getElementById('f_panjang').value.replace('.', ','),
                satuan: document.getElementById('f_satuan').value,
                biaya: parseInt(document.getElementById('f_biaya').value.replace(/[^0-9]/g, '')) || 25000000000,
                tematik: document.getElementById('f_tematik').value,
                keterangan: document.getElementById('f_keterangan').value,
                lat: -7.5360 + (Math.random() - 0.5) * 1.5,
                lng: 112.2384 + (Math.random() - 0.5) * 1.5,
                verifPU: true,
                verifBappenas: false,
                verifKemenko: true
            };

            usulanList.unshift(newUsulan);
            closeModalTambah();
            applyFilters();
            alert(`Sukses! Usulan Inpres #${newId} telah berhasil disimpan dan tercatat ke sistem E-Jalin.`);
        }

        // Modal: Peta Leaflet
        function openModalPeta(id) {
            const item = usulanList.find(x => x.id === id);
            if (!item) return;

            document.getElementById('petaTitle').innerText = item.ruas || item.kegiatan;
            document.getElementById('petaSubtitle').innerText = `${item.lokasi} | Panjang ${item.panjang} ${item.satuan}`;
            document.getElementById('modalPeta').classList.remove('hidden');

            setTimeout(() => {
                if (!leafletMap) {
                    leafletMap = L.map('mapContainer').setView([item.lat, item.lng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap Dinas PU Bina Marga Prov. Jatim'
                    }).addTo(leafletMap);
                } else {
                    leafletMap.invalidateSize();
                    leafletMap.setView([item.lat, item.lng], 13);
                }

                if (leafletMarker) {
                    leafletMap.removeLayer(leafletMarker);
                }

                leafletMarker = L.marker([item.lat, item.lng])
                    .addTo(leafletMap)
                    .bindPopup(`<b>${item.ruas}</b><br>${item.kegiatan}<br><span style="color:#0f2a71;font-weight:bold">${formatRupiah(item.biaya)}</span>`)
                    .openPopup();
            }, 200);
        }

        function closeModalPeta() {
            document.getElementById('modalPeta').classList.add('hidden');
        }

        function editUsulan(id) {
            const item = usulanList.find(x => x.id === id);
            if (!item) return;
            openModalDetail(id);
        }

        function deleteUsulan(id) {
            if (confirm(`Apakah Anda yakin ingin menghapus data Usulan Inpres ID #${id}?`)) {
                usulanList = usulanList.filter(x => x.id !== id);
                applyFilters();
            }
        }



        // Initial Load
        document.addEventListener('DOMContentLoaded', () => {
            applyFilters();
        });
    </script>
@endpush
