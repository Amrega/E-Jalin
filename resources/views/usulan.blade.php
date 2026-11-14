@extends('layouts.app')

@section('title', 'Usulan - E-Jalin Dinas PU Bina Marga Provinsi Jawa Timur')

@push('styles')
    <!-- Leaflet for interactive map modal -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
    <!-- Breadcrumbs -->
    <nav class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 mb-6 gap-2">
        <a href="{{ url('/dashboard') }}" class="text-[#0f2a71] hover:underline">USULAN PEMDA</a>
        <span class="text-slate-400">&gt;</span>
        <span class="text-[#b87500] font-extrabold">USULAN</span>
    </nav>

    <!-- Notification Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">✕</button>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs shadow-xs">
        <div class="font-bold mb-1 flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span>Terdapat beberapa kesalahan input:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-2 font-medium">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @php
        $currentUser = auth()->user();
        $isKabKotaUser = $currentUser && $currentUser->isKabupatenKota();
        $isUptUser = $currentUser && $currentUser->isUpt();
        $isVerifierUser = $currentUser && $currentUser->isVerifierRole();
        $canCreateUsulan = $currentUser && $currentUser->canManageUsulan();
        $userRoleName = $currentUser ? $currentUser->roleName() : '';
    @endphp

    <!-- Top Action Header & Buttons -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            @if($canCreateUsulan)
                <button onclick="openModalTambah()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition transform hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>+ Tambah Usulan</span>
                </button>
            @endif

            @if($isUptUser && $currentUser->uptDinas)
                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs font-bold">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>Daftar Tugas UPT: {{ strtoupper($currentUser->uptDinas->nama_upt) }} (Usulan Disetujui PU Turdal)</span>
                </div>
            @elseif($isKabKotaUser && $currentUser->kabupatenKota)
                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-blue-50 border border-blue-200 text-[#0f2a71] text-xs font-bold">
                    <svg class="w-4 h-4 text-[#ffb700]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Wilayah Akses: {{ strtoupper($currentUser->kabupatenKota->nama_kabupaten_kota) }} (Hanya Usulan Wilayah Sendiri)</span>
                </div>
            @elseif($isVerifierUser)
                <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 text-xs font-bold">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>
                        @if($userRoleName === 'pu_turdal')
                            Mode Role PU Turdal: Hanya Lihat Usulan &amp; Input Indikasi Ruas Prioritas (PU)
                        @elseif($userRoleName === 'bappeda')
                            Mode Role Bappeda: Hanya Lihat Usulan &amp; Input Indikasi Ruas Prioritas (Bappeda)
                        @elseif($userRoleName === 'pu_bintek')
                            Mode Role PU Bintek: Hanya Lihat Usulan &amp; Input Verifikasi (PU)
                        @endif
                    </span>
                </div>
            @endif
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

    <!-- Filter Panel Box -->
    <form method="GET" action="{{ route('usulan.index') }}" id="filterForm" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs mb-6">
        <input type="hidden" name="per_page" id="hiddenPerPage" value="{{ request('per_page', 10) }}">
        <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search') }}">

        <div class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <span>Filter Parameter Usulan</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 text-xs">
            <!-- 1. Provinsi -->
            <div>
                <label class="block font-bold text-slate-600 mb-1">Provinsi</label>
                <input type="text" value="JAWA TIMUR" disabled class="w-full bg-slate-100 border border-slate-300 rounded-lg px-3 py-2 text-slate-600 font-bold cursor-not-allowed">
            </div>

            <!-- 2. Kab/Kota -->
            <div>
                <label class="block font-bold text-slate-600 mb-1">Kab/Kota</label>
                <select name="kabupaten_kota_id" onchange="this.form.submit()" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-800 font-medium focus:outline-none focus:border-[#0f2a71]">
                    <option value="">-- Semua Kab/Kota --</option>
                    @foreach($kabupatenKotaList as $kk)
                        <option value="{{ $kk->id }}" {{ (string) request('kabupaten_kota_id') === (string) $kk->id ? 'selected' : '' }}>
                            {{ strtoupper($kk->nama_kabupaten_kota) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Nama Pengusul -->
            <div>
                <label class="block font-bold text-slate-600 mb-1">Nama Pengusul</label>
                <select name="nama_pengusul" onchange="this.form.submit()" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-800 font-medium focus:outline-none focus:border-[#0f2a71]">
                    <option value="">-- Semua Pengusul --</option>
                    @foreach($pengusulList as $pengusul)
                        <option value="{{ $pengusul }}" {{ request('nama_pengusul') === $pengusul ? 'selected' : '' }}>
                            {{ $pengusul }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 4. Tematik Kawasan -->
            <div>
                <label class="block font-bold text-slate-600 mb-1">Tematik Kawasan</label>
                <select name="tematik_kawasan_id" onchange="this.form.submit()" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-800 font-medium focus:outline-none focus:border-[#0f2a71]">
                    <option value="">-- Semua Tematik --</option>
                    @foreach($tematikKawasanList as $tk)
                        <option value="{{ $tk->id }}" {{ (string) request('tematik_kawasan_id') === (string) $tk->id ? 'selected' : '' }}>
                            {{ $tk->nama_tematik }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Reset Filter Action -->
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500 font-medium">
                Menampilkan {{ $usulanList->total() }} usulan sesuai filter.
            </span>
            @if(request()->anyFilled(['kabupaten_kota_id', 'nama_pengusul', 'tematik_kawasan_id', 'search', 'status']))
                <a href="{{ route('usulan.index') }}" class="text-rose-600 hover:text-rose-700 font-bold flex items-center gap-1 hover:underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Reset Filter</span>
                </a>
            @endif
        </div>
    </form>

    <!-- 4 Summary Metric Cards -->
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
            <div class="text-3xl font-black text-[#0f2a71] mt-3">{{ number_format($totalUsulan, 0, ',', '.') }}</div>
            <div class="text-[11px] text-slate-400 mt-1 font-medium">Usulan PEMDA Jatim Aktif</div>
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
            <div class="text-3xl font-black text-rose-600 mt-3">{{ number_format($totalPanjangJalan, 2, ',', '.') }} <span class="text-base font-bold text-slate-500">KM</span></div>
            <div class="text-[11px] text-slate-400 mt-1 font-medium">Ruas Jalan Strategis Provinsi &amp; Daerah</div>
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
            <div class="text-3xl font-black text-[#0f2a71] mt-3">{{ number_format($totalPanjangJembatan, 2, ',', '.') }} <span class="text-base font-bold text-slate-500">M</span></div>
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
            <div class="text-xl sm:text-2xl font-black text-emerald-700 mt-3 truncate" title="Rp {{ number_format($totalEstimasiBiaya, 0, ',', '.') }}">
                Rp {{ number_format($totalEstimasiBiaya, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1 font-medium">Total Anggaran Usulan Jawa Timur</div>
        </div>
    </div>

    <!-- Table Container Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Table Controls: Length & Search -->
        <form method="GET" action="{{ route('usulan.index') }}" class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <input type="hidden" name="kabupaten_kota_id" value="{{ request('kabupaten_kota_id') }}">
            <input type="hidden" name="nama_pengusul" value="{{ request('nama_pengusul') }}">
            <input type="hidden" name="tematik_kawasan_id" value="{{ request('tematik_kawasan_id') }}">

            <div class="flex items-center gap-2 text-xs text-slate-600 font-medium w-full sm:w-auto">
                <select name="per_page" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-slate-700 bg-white focus:outline-none">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>data per halaman</span>
            </div>

            <div class="relative w-full sm:w-80">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari ruas, ID usulan, kegiatan, kab/kota..."
                    class="w-full pl-3 pr-9 py-1.5 text-xs border border-slate-300 rounded-lg focus:outline-none focus:border-[#0f2a71] focus:ring-1 focus:ring-[#0f2a71]"
                >
                <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-[#0f2a71]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Main Scrollable Table -->
        <div class="overflow-x-auto custom-scrollbar" style="max-height: 700px;">
            <table id="table_usulan" class="w-full text-left border-collapse text-[11px] whitespace-nowrap">
                <thead>
                    <tr class="bg-[#0f2a71] text-white divide-x divide-[#153894] border-b border-[#ffb700]">
                        <th rowspan="2" class="p-3 text-center font-bold tracking-wider w-10">No.</th>
                        <th rowspan="2" class="p-3 text-center font-bold tracking-wider">ID Usulan</th>
                        <th rowspan="2" class="p-3 text-left font-bold tracking-wider">Lokasi</th>
                        <th rowspan="2" class="p-3 text-left font-bold tracking-wider">Nama Pengusul</th>
                        <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[220px]">Nama Kegiatan</th>
                        <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[180px]">Nama Koridor</th>
                        <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[200px]">Nama Ruas</th>
                        <th rowspan="2" class="p-3 text-center font-bold tracking-wider">Status Ruas</th>
                        <th rowspan="2" class="p-3 text-left font-bold tracking-wider">Nama Jembatan</th>
                        <th rowspan="2" class="p-3 text-right font-bold tracking-wider">Panjang Penanganan</th>
                        <th rowspan="2" class="p-3 text-center font-bold tracking-wider">Satuan</th>
                        <th rowspan="2" class="p-3 text-right font-bold tracking-wider">Alokasi Usulan (Rp)</th>
                        <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[160px]">Tematik Kawasan</th>
                        <th rowspan="2" class="p-3 text-left font-bold tracking-wider min-w-[200px]">Keterangan</th>
                        <th colspan="2" class="p-2.5 text-center font-bold tracking-wider bg-[#0a1e52] text-[#ffb700] border-b border-[#153894]">Indikasi Ruas Prioritas</th>
                        <th colspan="1" class="p-2.5 text-center font-bold tracking-wider bg-[#0a1e52] text-[#ffb700] border-b border-[#153894]">Verifikasi</th>
                        <th rowspan="2" class="p-3 text-center font-bold tracking-wider w-16">Peta</th>
                        <th rowspan="2" class="p-3 text-center font-bold tracking-wider w-28">Action</th>
                    </tr>
                    <tr class="bg-[#0b2158] text-[10px] text-slate-200 divide-x divide-[#153894]">
                        <!-- Indikasi Ruas Prioritas sub-columns -->
                        <th class="p-2 text-center font-semibold">PU</th>
                        <th class="p-2 text-center font-semibold">Bappeda</th>
                        <!-- Verifikasi sub-columns -->
                        <th class="p-2 text-center font-semibold">PU</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 text-slate-700 bg-white">
                    @php
                        $canPrioritasPu = auth()->check() && auth()->user()->canEditPrioritasPu();
                        $canPrioritasBappeda = auth()->check() && auth()->user()->canEditPrioritasBappeda();
                        $canVerifikasiPu = auth()->check() && auth()->user()->canEditVerifikasiPu();
                    @endphp
                    @forelse($usulanList as $index => $item)
                        @php
                            $lokasiText = 'JAWA TIMUR - ' . strtoupper($item->kabupatenKota->nama_kabupaten_kota ?? '-');
                            $koridorText = $item->koridor ? $item->koridor->nama_koridor : '---';
                            $ruasText = $item->ruasJalan ? $item->ruasJalan->nama_ruas : '---';
                            $jembatanText = $item->jembatan ? $item->jembatan->nama_jembatan : '---';
                            $tematikText = $item->tematikKawasan ? $item->tematikKawasan->nama_tematik : '-';

                            $kmlUrl = null;
                            if ($item->jembatan && $item->jembatan->file_kml_jembatan) {
                                $kmlUrl = asset('uploads/kml/jembatan/' . $item->jembatan->file_kml_jembatan);
                            } elseif ($item->ruasJalan && $item->ruasJalan->file_kml_ruas) {
                                $kmlUrl = asset('uploads/kml/jalan/' . $item->ruasJalan->file_kml_ruas);
                            } elseif ($item->koridor && $item->koridor->file_kml_koridor) {
                                $kmlUrl = asset('uploads/kml/koridor/' . $item->koridor->file_kml_koridor);
                            }
                        @endphp
                        <tr class="hover:bg-slate-50 transition border-b border-slate-200 divide-x divide-slate-100 {{ $item->void ? 'bg-slate-50/70 opacity-60' : '' }}">
                            <td class="p-2.5 text-center font-semibold text-slate-500">
                                {{ $usulanList->firstItem() + $index }}
                            </td>
                            <td class="p-2.5 text-center font-mono font-bold text-[#0f2a71] bg-slate-50/50">
                                {{ $item->kode_usulan ?? $item->id }}
                            </td>
                            <td class="p-2.5 font-medium text-slate-800">
                                {{ $lokasiText }}
                            </td>
                            <td class="p-2.5 text-slate-700">
                                {{ $item->nama_pengusul }}
                            </td>
                            <td class="p-2.5 font-bold text-[#0f2a71] whitespace-normal min-w-[220px] leading-tight {{ $item->void ? 'line-through' : '' }}">
                                {{ $item->nama_kegiatan }}
                            </td>
                            <td class="p-2.5 text-slate-600 whitespace-normal min-w-[180px] leading-snug">
                                {{ $koridorText }}
                            </td>
                            <td class="p-2.5 font-medium text-slate-800 whitespace-normal min-w-[200px] leading-snug">
                                {{ $ruasText }}
                            </td>
                            <td class="p-2.5 text-center font-semibold {{ $item->status_ruas === 'Provinsi' ? 'text-amber-800 bg-amber-50' : 'text-blue-800 bg-blue-50' }}">
                                {{ $item->status_ruas }}
                            </td>
                            <td class="p-2.5 text-slate-600">
                                {{ $jembatanText }}
                            </td>
                            <td class="p-2.5 text-right font-mono font-bold text-slate-800">
                                {{ number_format($item->panjang_penanganan, 2, ',', '.') }}
                            </td>
                            <td class="p-2.5 text-center font-bold text-slate-500">
                                {{ $item->satuan }}
                            </td>
                            <td class="p-2.5 text-right font-mono font-bold text-emerald-700">
                                Rp {{ number_format($item->alokasi_usulan, 0, ',', '.') }}
                            </td>
                            <td class="p-2.5 text-slate-700 whitespace-normal min-w-[160px] leading-snug">
                                {{ $tematikText }}
                            </td>
                            <td class="p-2.5 text-slate-500 whitespace-normal min-w-[200px] max-w-xs truncate" title="{{ $item->keterangan }}">
                                {{ $item->keterangan ?: '-' }}
                            </td>

                            <!-- Indikasi Ruas Prioritas: PU (Role: pu_turdal + Pilih UPT Tugas) -->
                            <td class="p-2.5 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    @if($canPrioritasPu)
                                        <button
                                            type="button"
                                            onclick="openModalStatus({{ $item->id }}, @js($item->kode_usulan ?? $item->id), @js($item->nama_kegiatan), 'prioritas_pu', 'Indikasi Ruas Prioritas - PU (PU Turdal)', {{ $item->prioritas_pu ? '1' : '0' }}, {{ $item->upt_dinas_id ?? ($item->kabupatenKota->upt_id ?? 'null') }})"
                                            title="Klik untuk ubah Indikasi Prioritas PU &amp; Pilih UPT"
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-lg font-extrabold text-xs border transition cursor-pointer {{ $item->prioritas_pu ? 'bg-emerald-50 text-emerald-700 border-emerald-300 hover:bg-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-200 hover:bg-rose-100' }}"
                                        >
                                            {!! $item->prioritas_pu ? '&#10003;' : '&#10005;' !!}
                                        </button>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-lg font-extrabold text-xs border {{ $item->prioritas_pu ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}"
                                            title="{{ $item->prioritas_pu ? 'Indikasi Prioritas PU: Disetujui' : 'Indikasi Prioritas PU: Belum (X)' }}"
                                        >
                                            {!! $item->prioritas_pu ? '&#10003;' : '&#10005;' !!}
                                        </span>
                                    @endif

                                    @if($item->prioritas_pu && $item->uptDinas)
                                        <span class="px-1.5 py-0.5 rounded bg-blue-50 text-[#0f2a71] border border-blue-200 text-[9px] font-bold leading-tight">
                                            {{ $item->uptDinas->nama_upt }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Indikasi Ruas Prioritas: Bappeda (Role: bappeda) -->
                            <td class="p-2.5 text-center">
                                @if($canPrioritasBappeda)
                                    <button
                                        type="button"
                                        onclick="openModalStatus({{ $item->id }}, @js($item->kode_usulan ?? $item->id), @js($item->nama_kegiatan), 'prioritas_bappeda', 'Indikasi Ruas Prioritas - Bappeda', {{ $item->prioritas_bappeda ? '1' : '0' }}, null)"
                                        title="Klik untuk ubah Indikasi Prioritas Bappeda"
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-lg font-extrabold text-xs border transition cursor-pointer {{ $item->prioritas_bappeda ? 'bg-emerald-50 text-emerald-700 border-emerald-300 hover:bg-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-200 hover:bg-rose-100' }}"
                                    >
                                        {!! $item->prioritas_bappeda ? '&#10003;' : '&#10005;' !!}
                                    </button>
                                @else
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-lg font-extrabold text-xs border {{ $item->prioritas_bappeda ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}"
                                        title="{{ $item->prioritas_bappeda ? 'Indikasi Prioritas Bappeda: Disetujui' : 'Indikasi Prioritas Bappeda: Belum (X)' }}"
                                    >
                                        {!! $item->prioritas_bappeda ? '&#10003;' : '&#10005;' !!}
                                    </span>
                                @endif
                            </td>

                            <!-- Verifikasi: PU (Role: pu_bintek) -->
                            <td class="p-2.5 text-center">
                                @if($canVerifikasiPu)
                                    <button
                                        type="button"
                                        onclick="openModalStatus({{ $item->id }}, @js($item->kode_usulan ?? $item->id), @js($item->nama_kegiatan), 'verifikasi_pu', 'Verifikasi - PU (PU Bintek)', {{ $item->verifikasi_pu ? '1' : '0' }}, null)"
                                        title="Klik untuk ubah Verifikasi PU"
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-lg font-extrabold text-xs border transition cursor-pointer {{ $item->verifikasi_pu ? 'bg-emerald-50 text-emerald-700 border-emerald-300 hover:bg-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-200 hover:bg-rose-100' }}"
                                    >
                                        {!! $item->verifikasi_pu ? '&#10003;' : '&#10005;' !!}
                                    </button>
                                @else
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-lg font-extrabold text-xs border {{ $item->verifikasi_pu ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}"
                                        title="{{ $item->verifikasi_pu ? 'Verifikasi PU: Terverifikasi' : 'Verifikasi PU: Belum (X)' }}"
                                    >
                                        {!! $item->verifikasi_pu ? '&#10003;' : '&#10005;' !!}
                                    </span>
                                @endif
                            </td>

                            <!-- Peta Pin -->
                            <td class="p-2.5 text-center">
                                <button
                                    type="button"
                                    onclick="openModalPeta(@js($item->nama_kegiatan), @js($lokasiText . ' | Panjang ' . number_format($item->panjang_penanganan, 2, ',', '.') . ' ' . $item->satuan), @js($kmlUrl))"
                                    title="Lihat Peta Spasial Geometri"
                                    class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </td>

                            <!-- Action Buttons -->
                            <td class="p-2.5 text-center">
                                <div class="inline-flex items-center gap-1">
                                    <button
                                        type="button"
                                        onclick="openModalDetail(@js($item), @js($lokasiText), @js($koridorText), @js($ruasText), @js($jembatanText), @js($tematikText))"
                                        title="Tinjau Detail Usulan"
                                        class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>

                                    @if($userRoleName === 'pu_turdal')
                                        <button
                                            type="button"
                                            onclick="openModalStatus({{ $item->id }}, @js($item->kode_usulan ?? $item->id), @js($item->nama_kegiatan), 'prioritas_pu', 'Indikasi Ruas Prioritas - PU (PU Turdal)', {{ $item->prioritas_pu ? '1' : '0' }}, {{ $item->upt_dinas_id ?? ($item->kabupatenKota->upt_id ?? 'null') }})"
                                            title="Form Indikasi Ruas Prioritas PU &amp; Pilih UPT"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#0f2a71] text-[#ffb700] hover:bg-navy-800 font-bold text-[10px] transition shadow-2xs"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>Indikasi PU</span>
                                        </button>
                                    @elseif($userRoleName === 'bappeda')
                                        <button
                                            type="button"
                                            onclick="openModalStatus({{ $item->id }}, @js($item->kode_usulan ?? $item->id), @js($item->nama_kegiatan), 'prioritas_bappeda', 'Indikasi Ruas Prioritas - Bappeda', {{ $item->prioritas_bappeda ? '1' : '0' }}, null)"
                                            title="Form Indikasi Ruas Prioritas Bappeda"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-purple-700 text-white hover:bg-purple-800 font-bold text-[10px] transition shadow-2xs"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>Indikasi Bappeda</span>
                                        </button>
                                    @elseif($userRoleName === 'pu_bintek')
                                        <button
                                            type="button"
                                            onclick="openModalStatus({{ $item->id }}, @js($item->kode_usulan ?? $item->id), @js($item->nama_kegiatan), 'verifikasi_pu', 'Verifikasi - PU (PU Bintek)', {{ $item->verifikasi_pu ? '1' : '0' }}, null)"
                                            title="Form Verifikasi PU"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-bold text-[10px] transition shadow-2xs"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>Verifikasi PU</span>
                                        </button>
                                    @endif

                                    @if($currentUser && $currentUser->canManageUsulan($item))
                                        <button
                                            type="button"
                                            onclick="openModalEdit(@js($item))"
                                            title="Edit Usulan"
                                            class="p-1.5 rounded-lg bg-blue-50 text-[#0f2a71] hover:bg-blue-100 transition"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button
                                            type="button"
                                            onclick="openModalHapus({{ $item->id }}, @js($item->nama_kegiatan))"
                                            title="Hapus Usulan"
                                            class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="19" class="p-8 text-center text-slate-400 font-bold bg-slate-50">
                                Tidak ada data usulan yang cocok dengan kriteria pencarian/filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer & Pagination -->
        <div class="p-4 sm:p-5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div class="text-slate-500 font-medium">
                @if($usulanList->total() > 0)
                    Menampilkan {{ $usulanList->firstItem() }} s/d {{ $usulanList->lastItem() }} dari {{ $usulanList->total() }} data
                @else
                    Menampilkan 0 data
                @endif
            </div>
            <div>
                {{ $usulanList->links() }}
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: DETAIL / TINJAU USULAN                                  -->
    <!-- ============================================================== -->
    <div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Detail Tinjau Usulan</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Data Terverifikasi E-Jalin Bina Marga Jawa Timur</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalDetail()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto space-y-6 text-xs text-slate-700 flex-grow" id="modalDetailBody">
                <!-- Dynamically populated -->
            </div>

            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <button type="button" onclick="closeModalDetail()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: TAMBAH / EDIT USULAN                                     -->
    <!-- ============================================================== -->
    <div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden">
            
            <!-- Header -->
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 id="modalFormTitle" class="text-base font-bold text-white leading-tight">Formulir Tambah Usulan Baru</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Pengusulan Penanganan Jalan &amp; Jembatan Daerah Provinsi Jawa Timur</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalTambah()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <form id="frmUsulan" method="POST" action="{{ route('usulan.store') }}" enctype="multipart/form-data" class="p-6 overflow-y-auto space-y-6 text-xs text-slate-700 flex-grow">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <!-- Notice Alert -->
                <div class="p-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-900">
                    <div class="font-bold mb-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Petunjuk Pengisian:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-amber-800">
                        <li>Desimal dipisah dengan "." (titik), contoh: <b>7.55</b>.</li>
                        <li>Semua kolom bertanda (<span class="text-red-500 font-bold">*</span>) wajib diisi.</li>
                    </ul>
                </div>

                <!-- Bagian 1: Ruas / Jembatan & Spesifikasi Teknis -->
                <div class="space-y-4">
                    <h4 class="font-extrabold text-[#0f2a71] text-xs uppercase tracking-wider pb-1 border-b border-slate-200 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#0f2a71] text-[#ffb700] inline-flex items-center justify-center text-[10px]">1</span>
                        <span>Ruas / Jembatan &amp; Spesifikasi Teknis</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kegiatan" id="f_kegiatan" required placeholder="Contoh: Preservasi Jalan Ruas Bts. Kota Lumajang - Bts.Kab Jember" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tematik Kawasan <span class="text-red-500">*</span></label>
                            <select name="tematik_kawasan_id" id="f_tematik" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="">-- Pilih Tematik Kawasan --</option>
                                @foreach($tematikKawasanList as $tk)
                                    <option value="{{ $tk->id }}">{{ $tk->nama_tematik }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jenis Penanganan <span class="text-red-500">*</span></label>
                            <select name="jenis_penanganan_id" id="f_jenis_penanganan" onchange="toggleBridgeField()" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="">-- Pilih Jenis Penanganan --</option>
                                @foreach($jenisPenangananList as $jp)
                                    <option value="{{ $jp->id }}" data-target="{{ $jp->target_penangan }}">
                                        [{{ strtoupper($jp->target_penangan) }}] {{ $jp->nama_penanganan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <select name="kabupaten_kota_id" id="f_kabkota" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                @if(!$isKabKotaUser)
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                @endif
                                @foreach($kabupatenKotaList as $kk)
                                    <option value="{{ $kk->id }}" {{ ($isKabKotaUser && (int) $currentUser->kabupaten_kota_id === (int) $kk->id) ? 'selected' : '' }}>
                                        {{ $kk->nama_kabupaten_kota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Pengusul <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pengusul" id="f_pengusul" required value="{{ $isKabKotaUser && $currentUser->kabupatenKota ? (str_starts_with(strtolower($currentUser->kabupatenKota->nama_kabupaten_kota), 'kota') ? 'Walikota ' . substr($currentUser->kabupatenKota->nama_kabupaten_kota, 5) : 'Bupati ' . substr($currentUser->kabupatenKota->nama_kabupaten_kota, 10)) : 'Gubernur Provinsi Jawa Timur' }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Koridor</label>
                            <select name="koridor_id" id="f_koridor" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="">-- Non-Koridor / Pilih Koridor --</option>
                                @foreach($koridorList as $kr)
                                    <option value="{{ $kr->id }}">{{ $kr->nomor_koridor ? '['.$kr->nomor_koridor.'] ' : '' }}{{ $kr->nama_koridor }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Ruas (Referensi)</label>
                            <select name="ruas_jalan_id" id="f_ruas" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="">-- Pilih Ruas Jalan --</option>
                                @foreach($ruasJalanList as $rj)
                                    <option value="{{ $rj->id }}">{{ $rj->nomor_ruas ? '['.$rj->nomor_ruas.'] ' : '' }}{{ $rj->nama_ruas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="fieldJembatanWrap" class="hidden">
                            <label class="block font-bold text-slate-700 mb-1">Nama Jembatan (Referensi)</label>
                            <select name="jembatan_id" id="f_jembatan" class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="">-- Pilih Jembatan --</option>
                                @foreach($jembatanList as $jb)
                                    <option value="{{ $jb->id }}">{{ $jb->nomor_jembatan ? '['.$jb->nomor_jembatan.'] ' : '' }}{{ $jb->nama_jembatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Status Ruas <span class="text-red-500">*</span></label>
                            <select name="status_ruas" id="f_statusruas" required class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                                <option value="Provinsi">Provinsi</option>
                                <option value="Non Status">Non Status</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Panjang Penanganan <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-2">
                                <input type="number" step="0.01" name="panjang_penanganan" id="f_panjang" required placeholder="Contoh: 3.50" class="flex-grow border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]">
                                <select name="satuan" id="f_satuan" class="w-24 border border-slate-300 rounded-lg px-3 py-2 font-bold bg-slate-50">
                                    <option value="KM" selected>KM</option>
                                    <option value="M">M</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Alokasi Biaya Usulan (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="alokasi_usulan" id="f_biaya" required placeholder="Contoh: 25000000000" class="w-full border border-slate-300 rounded-lg px-3 py-2 font-bold text-[#0f2a71] focus:outline-none focus:border-[#0f2a71]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Keterangan / Justifikasi Usulan</label>
                            <textarea name="keterangan" id="f_keterangan" rows="2" placeholder="Catatan dukungan kawasan pangan, industri, konektivitas, dll." class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:border-[#0f2a71]"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Bagian 2: Dokumen Kelengkapan -->
                <div class="space-y-4">
                    <h4 class="font-extrabold text-[#0f2a71] text-xs uppercase tracking-wider pb-1 border-b border-slate-200 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#0f2a71] text-[#ffb700] inline-flex items-center justify-center text-[10px]">2</span>
                        <span>Dokumen Kelengkapan</span>
                    </h4>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <label class="block font-bold text-slate-700 mb-1">Dokumen Pendukung / Teknis <span class="text-red-500">*</span></label>
                            <select name="status_dokumen_pendukung" id="f_status_dokumen" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 mb-2 focus:outline-none bg-white">
                                <option value="SIAP">SIAP</option>
                                <option value="TIDAK SIAP">TIDAK SIAP</option>
                                <option value="TIDAK PERLU">TIDAK PERLU</option>
                            </select>
                            <input type="file" name="file_dokumen_pendukung" accept=".pdf,.doc,.docx,.zip" class="w-full text-[11px] text-slate-500">
                            <div id="infoFileDokumen" class="mt-1 text-[11px] text-slate-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Action Submit Buttons -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModalTambah()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-sm transition">
                        Simpan Usulan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: UBAH STATUS INDIKASI PRIORITAS / VERIFIKASI SESUAI ROLE -->
    <!-- ============================================================== -->
    <div id="modalStatus" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 id="modalStatusTitle" class="text-sm font-bold text-white leading-tight">Ubah Status Verifikasi / Indikasi</h3>
                        <p id="modalStatusSubtitle" class="text-[10px] text-slate-300 font-medium">Usulan #</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalStatus()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="formUpdateStatus" method="POST" class="p-6 space-y-4 text-xs text-slate-700">
                @csrf
                <input type="hidden" name="field" id="status_field">

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nama Kegiatan</span>
                    <span id="status_nama_kegiatan" class="font-bold text-[#0f2a71] text-xs block mt-0.5"></span>
                </div>

                <div>
                    <label id="status_label" class="block font-bold text-slate-700 mb-2">Pilih Status:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-emerald-200 bg-emerald-50/60 cursor-pointer hover:bg-emerald-100/70 transition">
                            <input type="radio" name="value" id="status_val_true" value="1" onchange="toggleUptSelector()" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                            <span class="font-extrabold text-emerald-800">&#10003; Disetujui / Ya</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-rose-200 bg-rose-50/60 cursor-pointer hover:bg-rose-100/70 transition">
                            <input type="radio" name="value" id="status_val_false" value="0" onchange="toggleUptSelector()" class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                            <span class="font-extrabold text-rose-700">&#10005; Belum / Tidak (X)</span>
                        </label>
                    </div>
                </div>

                <!-- Pilihan UPT Penerima Tugas (Khusus saat PU Turdal Menyetujui Indikasi Prioritas PU) -->
                <div id="wrapStatusUpt" class="hidden p-3.5 rounded-xl bg-blue-50/70 border border-blue-200 space-y-1.5">
                    <label class="block font-bold text-[#0f2a71]">
                        Pilih UPT PJJ Penerima Tugas <span class="text-red-500">*</span>
                    </label>
                    <select name="upt_dinas_id" id="status_upt_dinas_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 bg-white text-slate-800 font-semibold focus:outline-none focus:border-[#0f2a71]">
                        <option value="">-- Pilih UPT PJJ yang Mendapat Tugas --</option>
                        @foreach($uptDinasList ?? [] as $upt)
                            <option value="{{ $upt->id }}">{{ $upt->nama_upt }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-500">UPT yang dipilih akan menerima tugas penanganan usulan ini pada halaman Usulan mereka.</p>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalStatus()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#0f2a71] hover:bg-navy-800 text-[#ffb700] font-extrabold shadow-sm transition">
                        Simpan Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: KONFIRMASI HAPUS                                        -->
    <!-- ============================================================== -->
    <div id="modalHapus" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Hapus Data Usulan?</h3>
                <p id="hapusTextPrompt" class="text-xs text-slate-500 mb-6 leading-relaxed"></p>
                <form id="formHapus" method="POST" class="flex items-center justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="closeModalHapus()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-sm transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
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
                        <p id="petaSubtitle" class="text-[10px] text-slate-300 font-medium">Jalur Geospasial Usulan Jatim</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalPeta()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div id="mapContainer" class="flex-grow w-full bg-slate-200 relative z-0"></div>

            <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
                <span>Koordinat Geografis Jaringan Jalan Dinas PU Bina Marga Provinsi Jawa Timur</span>
                <button type="button" onclick="closeModalPeta()" class="px-4 py-1.5 bg-[#0f2a71] text-white rounded-lg font-bold hover:bg-navy-800 transition">
                    Tutup Peta
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js"></script>
    <script>
        let leafletMap = null;
        let currentKmlLayer = null;

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number || 0);
        }

        function toggleBridgeField() {
            const select = document.getElementById('f_jenis_penanganan');
            const selectedOpt = select.options[select.selectedIndex];
            const target = selectedOpt ? selectedOpt.getAttribute('data-target') : '';
            const wrap = document.getElementById('fieldJembatanWrap');

            if (target === 'jembatan') {
                wrap.classList.remove('hidden');
                document.getElementById('f_satuan').value = 'M';
            } else {
                wrap.classList.add('hidden');
                document.getElementById('f_jembatan').value = '';
                document.getElementById('f_satuan').value = 'KM';
            }
        }

        function openModalTambah() {
            const form = document.getElementById('frmUsulan');
            form.reset();
            form.action = "{{ route('usulan.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('modalFormTitle').innerText = 'Formulir Tambah Usulan Baru';
            document.getElementById('infoFileDokumen').innerHTML = '';
            toggleBridgeField();
            document.getElementById('modalTambah').classList.remove('hidden');
        }

        function openModalEdit(item) {
            const form = document.getElementById('frmUsulan');
            form.reset();
            form.action = `/usulan/${item.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('modalFormTitle').innerText = `Edit Usulan #${item.kode_usulan || item.id}`;

            document.getElementById('f_kegiatan').value = item.nama_kegiatan || '';
            document.getElementById('f_tematik').value = item.tematik_kawasan_id || '';
            document.getElementById('f_jenis_penanganan').value = item.jenis_penanganan_id || '';
            document.getElementById('f_kabkota').value = item.kabupaten_kota_id || '';
            document.getElementById('f_pengusul').value = item.nama_pengusul || '';
            document.getElementById('f_koridor').value = item.koridor_id || '';
            document.getElementById('f_ruas').value = item.ruas_jalan_id || '';
            toggleBridgeField();
            document.getElementById('f_jembatan').value = item.jembatan_id || '';
            document.getElementById('f_statusruas').value = item.status_ruas || 'Kabupaten/Kota';
            document.getElementById('f_panjang').value = item.panjang_penanganan || 0;
            document.getElementById('f_satuan').value = item.satuan || 'KM';
            document.getElementById('f_biaya').value = item.alokasi_usulan || 0;
            document.getElementById('f_keterangan').value = item.keterangan || '';
            document.getElementById('f_status_dokumen').value = item.status_dokumen_pendukung || 'SIAP';

            const infoDok = document.getElementById('infoFileDokumen');
            if (item.file_dokumen_pendukung) {
                infoDok.innerHTML = `File saat ini: <strong class="text-emerald-700">${item.file_dokumen_pendukung}</strong> (biarkan kosong jika tidak diubah)`;
            } else {
                infoDok.innerHTML = `<span class="italic text-slate-400">Belum ada file dokumen yang diupload</span>`;
            }

            document.getElementById('modalTambah').classList.remove('hidden');
        }

        function closeModalTambah() {
            document.getElementById('modalTambah').classList.add('hidden');
        }

        function openModalDetail(item, lokasiText, koridorText, ruasText, jembatanText, tematikText) {
            const modalBody = document.getElementById('modalDetailBody');
            const downloadBtn = item.file_dokumen_pendukung
                ? `<a href="/usulan/${item.id}/download-dokumen" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#0f2a71] text-white font-bold hover:bg-navy-800 transition">
                    <span>Download Dokumen</span>
                   </a>`
                : '';

            modalBody.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">ID Usulan</span>
                        <span class="text-base font-mono font-black text-[#0f2a71]">${item.kode_usulan || item.id}</span>
                        <div class="mt-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nama Kegiatan</span>
                            <span class="font-extrabold text-[#0f2a71] text-sm">${item.nama_kegiatan}</span>
                        </div>
                        <div class="mt-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Lokasi &amp; Pengusul</span>
                            <span class="font-semibold text-slate-700 block">${lokasiText}</span>
                            <span class="text-slate-500 font-medium">${item.nama_pengusul}</span>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Alokasi Anggaran Usulan</span>
                        <span class="text-xl font-mono font-black text-emerald-700">${formatRupiah(item.alokasi_usulan)}</span>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Panjang Penanganan</span>
                                <span class="font-bold text-slate-800">${item.panjang_penanganan} ${item.satuan}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Ruas</span>
                                <span class="font-bold text-amber-700">${item.status_ruas}</span>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tematik Kawasan</span>
                            <span class="font-bold text-[#0f2a71]">${tematikText}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nama Koridor &amp; Ruas</span>
                            <span class="font-semibold text-slate-800 block">${koridorText}</span>
                            <span class="text-slate-600">${ruasText} ${jembatanText !== '---' ? ' | Jembatan: ' + jembatanText : ''}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Keterangan / Justifikasi Teknis</span>
                            <p class="text-slate-600 text-xs leading-relaxed mt-0.5">${item.keterangan || 'Tidak ada keterangan tambahan.'}</p>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-1">Dokumen Kelengkapan</span>
                            <span class="inline-block px-3 py-1 bg-emerald-50 rounded-lg border border-emerald-200 text-emerald-800 font-bold text-xs">
                                Dokumen Pendukung / Teknis: ${item.status_dokumen_pendukung || 'SIAP'}
                            </span>
                        </div>
                        <div>${downloadBtn}</div>
                    </div>
                </div>
            `;
            document.getElementById('modalDetail').classList.remove('hidden');
        }

        function closeModalDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        function toggleUptSelector() {
            const field = document.getElementById('status_field').value;
            const isApproved = document.getElementById('status_val_true').checked;
            const wrapUpt = document.getElementById('wrapStatusUpt');
            const selectUpt = document.getElementById('status_upt_dinas_id');

            if (field === 'prioritas_pu' && isApproved) {
                wrapUpt.classList.remove('hidden');
                selectUpt.required = true;
            } else {
                wrapUpt.classList.add('hidden');
                selectUpt.required = false;
            }
        }

        function openModalStatus(id, kodeUsulan, namaKegiatan, field, labelTitle, currentVal, defaultUptId = null) {
            const form = document.getElementById('formUpdateStatus');
            form.action = `/usulan/${id}/update-status`;
            document.getElementById('status_field').value = field;
            document.getElementById('modalStatusTitle').innerText = labelTitle;
            document.getElementById('modalStatusSubtitle').innerText = `ID Usulan #${kodeUsulan}`;
            document.getElementById('status_nama_kegiatan').innerText = namaKegiatan;
            document.getElementById('status_label').innerText = `Ubah Status ${labelTitle}:`;

            if (Number(currentVal) === 1) {
                document.getElementById('status_val_true').checked = true;
            } else {
                document.getElementById('status_val_false').checked = true;
            }

            const selectUpt = document.getElementById('status_upt_dinas_id');
            if (selectUpt) {
                selectUpt.value = defaultUptId ? String(defaultUptId) : '';
            }

            toggleUptSelector();
            document.getElementById('modalStatus').classList.remove('hidden');
        }

        function closeModalStatus() {
            document.getElementById('modalStatus').classList.add('hidden');
        }

        function openModalHapus(id, namaKegiatan) {
            document.getElementById('formHapus').action = `/usulan/${id}`;
            document.getElementById('hapusTextPrompt').innerHTML = `Apakah Anda yakin ingin menghapus usulan <strong>"${namaKegiatan}"</strong>?`;
            document.getElementById('modalHapus').classList.remove('hidden');
        }

        function closeModalHapus() {
            document.getElementById('modalHapus').classList.add('hidden');
        }

        function openModalPeta(title, subtitle, kmlUrl) {
            document.getElementById('petaTitle').innerText = title;
            document.getElementById('petaSubtitle').innerText = subtitle;
            document.getElementById('modalPeta').classList.remove('hidden');

            setTimeout(() => {
                if (!leafletMap) {
                    leafletMap = L.map('mapContainer').setView([-7.5360, 112.2384], 8);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap Dinas PU Bina Marga Prov. Jatim'
                    }).addTo(leafletMap);
                } else {
                    leafletMap.invalidateSize();
                }

                if (currentKmlLayer) {
                    leafletMap.removeLayer(currentKmlLayer);
                    currentKmlLayer = null;
                }

                if (kmlUrl && typeof omnivore !== 'undefined') {
                    currentKmlLayer = omnivore.kml(kmlUrl)
                        .on('ready', function () {
                            if (currentKmlLayer.getBounds().isValid()) {
                                leafletMap.fitBounds(currentKmlLayer.getBounds(), { padding: [30, 30] });
                            }
                        })
                        .addTo(leafletMap);
                } else {
                    leafletMap.setView([-7.5360, 112.2384], 8);
                }
            }, 200);
        }

        function closeModalPeta() {
            document.getElementById('modalPeta').classList.add('hidden');
        }

        function exportCSV() {
            const rows = document.querySelectorAll('#table_usulan tbody tr');
            let csv = "No,ID Usulan,Lokasi,Nama Pengusul,Nama Kegiatan,Nama Koridor,Nama Ruas,Status Ruas,Nama Jembatan,Panjang,Satuan,Alokasi Usulan,Tematik Kawasan,Keterangan\n";
            rows.forEach(tr => {
                const cols = tr.querySelectorAll('td');
                if (cols.length >= 14) {
                    const line = Array.from(cols).slice(0, 14).map(td => `"${td.innerText.trim().replace(/"/g, '""')}"`).join(',');
                    csv += line + "\n";
                }
            });
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = `Usulan_Jatim_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function exportExcel() {
            exportCSV();
        }
    </script>
@endpush
