@extends('layouts.app')

@section('title', 'Usulan Aspal - E-Jalin Dinas PU Bina Marga Provinsi Jawa Timur')

@section('content')
    <!-- Breadcrumbs -->
    <nav class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 mb-6 gap-2">
        <span class="text-[#0f2a71] hover:underline cursor-pointer">USULAN PEMDA</span>
        <span class="text-slate-400">&gt;</span>
        <span class="text-[#b87500] font-extrabold">USULAN ASPAL</span>
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
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 cursor-pointer">✕</button>
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
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 cursor-pointer">✕</button>
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

    <!-- Top Action Header & Buttons -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            @if($canManage)
                <button onclick="openModalTambah()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Usulan Aspal</span>
                </button>
            @endif
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('usulan-aspal.index') }}" title="Refresh Halaman" class="p-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Table Container Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">

        <!-- Filter Controls Form -->
        <form method="GET" action="{{ route('usulan-aspal.index') }}" class="p-4 sm:p-5 border-b border-slate-200 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 text-xs bg-slate-50/50">

            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Per Page -->
                <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                    <select name="per_page" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-2.5 py-1.5 bg-white font-bold text-[#0f2a71] focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span>data</span>
                </div>

                <!-- Filter Kabupaten / Kota -->
                @if(!(auth()->user()->isKabupatenKota() && auth()->user()->kabupatenKota))
                <div class="flex items-center">
                    <select name="kabupaten_kota" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-3 py-1.5 bg-white font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                        <option value="">-- Semua Kab/Kota --</option>
                        @foreach($kabupatenKotaList as $kk)
                            <option value="{{ $kk->nama_kabupaten_kota }}" {{ request('kabupaten_kota') === $kk->nama_kabupaten_kota ? 'selected' : '' }}>
                                {{ $kk->nama_kabupaten_kota }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Filter Jenis Pengusul -->
                @if(auth()->user()->isAdmin() || $jenisPengusulList->count() > 1)
                <div class="flex items-center">
                    <select name="jenis_pengusul_id" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-3 py-1.5 bg-white font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                        <option value="">-- Semua Jenis Pengusul --</option>
                        @foreach($jenisPengusulList as $jenis)
                            <option value="{{ $jenis->id }}" {{ request('jenis_pengusul_id') == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis_pengusul }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(request()->anyFilled(['search', 'kabupaten_kota', 'jenis_pengusul_id', 'pengusul_id']))
                    <a href="{{ route('usulan-aspal.index') }}" class="px-2.5 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold transition flex items-center gap-1">
                        <span>Reset</span>
                        ✕
                    </a>
                @endif
            </div>

            <!-- Search Input -->
            <div class="flex items-center gap-2">
                <div class="relative w-full lg:w-72">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kab/kota, pengusul, urgensi..."
                        class="w-full border border-slate-300 rounded-lg pl-3 pr-8 py-1.5 bg-white text-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                    >
                    <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#0f2a71] cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

        </form>

        <!-- Data Table -->
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left text-xs text-slate-700 border-collapse">
                <thead class="bg-slate-50/90 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200 select-none">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">NO</th>
                        <th class="py-3.5 px-4">KABUPATEN / KOTA</th>
                        <th class="py-3.5 px-4">PENGUSUL</th>
                        <th class="py-3.5 px-4">JENIS PENGUSUL</th>
                        <th class="py-3.5 px-4 text-center">QTY (DRUM)</th>
                        <th class="py-3.5 px-4">KETERANGAN URGENSI</th>
                        <th class="py-3.5 px-4 text-center">SURAT PERMOHONAN</th>
                        <th class="py-3.5 px-4">TANGGAL DIBUAT</th>
                        <th class="py-3.5 px-4 text-center w-36">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($usulanAspalList as $index => $item)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-3 px-4 text-center text-slate-400 font-mono">
                                {{ $usulanAspalList->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900">
                                    {{ $item->kabupaten_kota }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($item->pengusul)
                                    <span class="font-bold text-slate-800">{{ $item->pengusul->nama_pengusul }}</span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($item->jenisPengusul)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                        {{ $item->jenisPengusul->nama_jenis_pengusul }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 font-mono">
                                    {{ number_format((int) $item->qty_drum, 0, ',', '.') }} Drum
                                </span>
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                <p class="text-slate-700 line-clamp-2" title="{{ $item->keterangan_urgensi }}">
                                    {{ $item->keterangan_urgensi }}
                                </p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($item->upload_surat_permohonan)
                                    <a href="{{ route('usulan-aspal.download-surat', $item->id) }}" title="{{ $item->upload_surat_permohonan }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-bold text-[11px] transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span>Unduh</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Belum diunggah</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500 text-[11px]">
                                {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <button
                                        type="button"
                                        onclick='openModalDetail(@json($item))'
                                        title="Lihat Detail"
                                        class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 hover:bg-[#0f2a71] hover:text-[#ffb700] flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    @if($canManage)
                                        <button
                                            type="button"
                                            onclick='openModalEdit(@json($item))'
                                            title="Ubah Usulan Aspal"
                                            class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3d99] flex items-center justify-center transition shadow-2xs cursor-pointer"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            onclick='openModalHapus({{ $item->id }}, @json($item->kabupaten_kota))'
                                            title="Hapus Usulan Aspal"
                                            class="w-7 h-7 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition shadow-2xs cursor-pointer"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-bold text-slate-600">Belum ada data usulan aspal</span>
                                    @if($canManage)
                                        <span class="text-xs text-slate-400">Silakan klik tombol <strong>+ Tambah Usulan Aspal</strong> di atas untuk menambahkan data baru.</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        @if($usulanAspalList->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <div>
                    Menampilkan <span class="font-bold text-slate-800">{{ $usulanAspalList->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $usulanAspalList->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-800">{{ $usulanAspalList->total() }}</span> data
                </div>
                <div>
                    {{ $usulanAspalList->links() }}
                </div>
            </div>
        @endif

    </div>

    @if($canManage)
    <!-- ============================================================== -->
    <!-- MODAL: TAMBAH USULAN ASPAL                                    -->
    <!-- ============================================================== -->
    <div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">

            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Tambah Usulan Aspal</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Penambahan Data Usulan Aspal</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalTambah()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition cursor-pointer">
                    ✕
                </button>
            </div>

            <form action="{{ route('usulan-aspal.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-grow overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 flex-grow">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Kabupaten / Kota <span class="text-red-500">*</span>
                        </label>
                        <select name="kabupaten_kota" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                            @if(!(auth()->user()->isKabupatenKota() && auth()->user()->kabupatenKota))
                                <option value="">-- Pilih Kabupaten / Kota --</option>
                            @endif
                            @foreach($kabupatenKotaList as $kk)
                                <option value="{{ $kk->nama_kabupaten_kota }}" {{ old('kabupaten_kota') === $kk->nama_kabupaten_kota ? 'selected' : '' }}>
                                    {{ $kk->nama_kabupaten_kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $singlePengusul = (!auth()->user()->isAdmin() && $pengusulList->count() === 1) ? $pengusulList->first() : null;
                        $defaultPengusulId = old('pengusul_id', $singlePengusul?->id);
                        $defaultJenisId = old('jenis_pengusul_id', $singlePengusul?->jenis_pengusul_id);
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">
                                Pengusul <span class="text-red-500">*</span>
                            </label>
                            <select id="tambah_pengusul_id" name="pengusul_id" required onchange="syncJenisByPengusul('tambah')" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                                @if(!$singlePengusul)
                                    <option value="">-- Pilih Pengusul --</option>
                                @endif
                                @foreach($pengusulList as $p)
                                    <option value="{{ $p->id }}" data-jenis="{{ $p->jenis_pengusul_id }}" {{ (string) $defaultPengusulId === (string) $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_pengusul }}
                                    </option>
                                @endforeach
                            </select>
                            @if($pengusulList->isEmpty())
                                <p class="text-[10px] text-amber-600 mt-1">Belum ada data Pengusul yang tertaut dengan akun Anda. Hubungi Administrator untuk mendaftarkannya di Master &rarr; Pengusul.</p>
                            @endif
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">
                                Jenis Pengusul <span class="text-red-500">*</span>
                            </label>
                            <select id="tambah_jenis_pengusul_id" name="jenis_pengusul_id" required onchange="syncPengusulByJenis('tambah')" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                                @if(!$singlePengusul)
                                    <option value="">-- Pilih Jenis Pengusul --</option>
                                @endif
                                @foreach($jenisPengusulList as $jenis)
                                    <option value="{{ $jenis->id }}" {{ (string) $defaultJenisId === (string) $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_jenis_pengusul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Qty (Drum) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                name="qty_drum"
                                value="{{ old('qty_drum') }}"
                                min="1"
                                step="1"
                                required
                                placeholder="Contoh: 50"
                                class="w-full border border-slate-300 rounded-xl pl-3 pr-16 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] font-bold text-slate-400">Drum</span>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Keterangan Urgensi <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="keterangan_urgensi"
                            rows="3"
                            required
                            placeholder="Jelaskan kondisi jalan dan urgensi kebutuhan aspal..."
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]"
                        >{{ old('keterangan_urgensi') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Upload Surat Permohonan
                        </label>
                        <input
                            type="file"
                            name="upload_surat_permohonan"
                            accept=".pdf,application/pdf"
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0f2a71] file:text-white hover:file:bg-[#1a3d99] cursor-pointer"
                        >
                        <p class="text-[10px] text-slate-400 mt-1">Format: hanya PDF (.pdf), maks. 25 MB.</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalTambah()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition shadow-xs cursor-pointer">Simpan Usulan Aspal</button>
                </div>
            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: EDIT USULAN ASPAL                                      -->
    <!-- ============================================================== -->
    <div id="modalEdit" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">

            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Ubah Usulan Aspal</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Perbarui Data Usulan Aspal</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalEdit()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition cursor-pointer">✕</button>
            </div>

            <form id="formEdit" method="POST" enctype="multipart/form-data" class="flex flex-col flex-grow overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 flex-grow">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Kabupaten / Kota <span class="text-red-500">*</span>
                        </label>
                        <select id="edit_kabupaten_kota" name="kabupaten_kota" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                            @if(!(auth()->user()->isKabupatenKota() && auth()->user()->kabupatenKota))
                                <option value="">-- Pilih Kabupaten / Kota --</option>
                            @endif
                            @foreach($kabupatenKotaList as $kk)
                                <option value="{{ $kk->nama_kabupaten_kota }}">
                                    {{ $kk->nama_kabupaten_kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">
                                Pengusul <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_pengusul_id" name="pengusul_id" required onchange="syncJenisByPengusul('edit')" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                                @if(!$singlePengusul)
                                    <option value="">-- Pilih Pengusul --</option>
                                @endif
                                @foreach($pengusulList as $p)
                                    <option value="{{ $p->id }}" data-jenis="{{ $p->jenis_pengusul_id }}">
                                        {{ $p->nama_pengusul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">
                                Jenis Pengusul <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_jenis_pengusul_id" name="jenis_pengusul_id" required onchange="syncPengusulByJenis('edit')" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                                @if(!$singlePengusul)
                                    <option value="">-- Pilih Jenis Pengusul --</option>
                                @endif
                                @foreach($jenisPengusulList as $jenis)
                                    <option value="{{ $jenis->id }}">
                                        {{ $jenis->nama_jenis_pengusul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Qty (Drum) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                id="edit_qty_drum"
                                name="qty_drum"
                                min="1"
                                step="1"
                                required
                                placeholder="Contoh: 50"
                                class="w-full border border-slate-300 rounded-xl pl-3 pr-16 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] font-bold text-slate-400">Drum</span>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Keterangan Urgensi <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="edit_keterangan_urgensi"
                            name="keterangan_urgensi"
                            rows="3"
                            required
                            placeholder="Jelaskan kondisi jalan dan urgensi kebutuhan aspal..."
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Upload Surat Permohonan Baru <span class="text-[10px] font-normal text-slate-400">(Opsional)</span>
                        </label>
                        <div id="edit_current_file_info" class="mb-2 hidden"></div>
                        <input
                            type="file"
                            name="upload_surat_permohonan"
                            accept=".pdf,application/pdf"
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0f2a71] file:text-white hover:file:bg-[#1a3d99] cursor-pointer"
                        >
                        <p class="text-[10px] text-slate-400 mt-1">Format: hanya PDF (.pdf), maks. 25 MB. Biarkan kosong jika tidak ingin mengganti surat permohonan yang sudah ada.</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalEdit()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#0f2a71] hover:bg-[#1a3d99] text-white font-bold transition shadow-xs cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: KONFIRMASI HAPUS                                       -->
    <!-- ============================================================== -->
    <div id="modalHapus" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Usulan Aspal?</h3>
                <p class="text-xs text-slate-500 mb-4" id="hapusTextPrompt">
                    Apakah Anda yakin ingin menghapus data usulan aspal ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center justify-center gap-2.5">
                        <button type="button" onclick="closeModalHapus()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-100 transition cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs transition shadow-xs cursor-pointer">Ya, Hapus Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- ============================================================== -->
    <!-- MODAL: DETAIL USULAN ASPAL                                    -->
    <!-- ============================================================== -->
    <div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden">

            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Detail Usulan Aspal</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Informasi Pengajuan Usulan Aspal</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalDetail()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition cursor-pointer">✕</button>
            </div>

            <div class="p-6 space-y-4 text-xs text-slate-700 flex-grow" id="modalDetailBody"></div>

            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end">
                <button type="button" onclick="closeModalDetail()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition cursor-pointer">Tutup</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }

    const isAdminUser = @json(auth()->user()->isAdmin());

    // Sinkronisasi dropdown Jenis Pengusul <-> Pengusul
    function syncPengusulByJenis(prefix) {
        const jenisSelect = document.getElementById(`${prefix}_jenis_pengusul_id`);
        const pengusulSelect = document.getElementById(`${prefix}_pengusul_id`);
        if (!jenisSelect || !pengusulSelect) return;

        const selectedJenis = jenisSelect.value;
        Array.from(pengusulSelect.options).forEach(opt => {
            if (!opt.value) {
                opt.hidden = false;
                return;
            }
            const optJenis = opt.dataset.jenis || '';
            const match = !selectedJenis || optJenis === selectedJenis;
            opt.hidden = !match;
        });

        const currentOption = pengusulSelect.options[pengusulSelect.selectedIndex];
        if (currentOption && currentOption.hidden) {
            pengusulSelect.value = '';
        }
    }

    function syncJenisByPengusul(prefix) {
        const jenisSelect = document.getElementById(`${prefix}_jenis_pengusul_id`);
        const pengusulSelect = document.getElementById(`${prefix}_pengusul_id`);
        if (!jenisSelect || !pengusulSelect) return;

        const selectedOpt = pengusulSelect.options[pengusulSelect.selectedIndex];
        const linkedJenis = selectedOpt ? (selectedOpt.dataset.jenis || '') : '';

        if (!isAdminUser) {
            Array.from(jenisSelect.options).forEach(opt => {
                if (!opt.value) {
                    opt.hidden = !!linkedJenis;
                    return;
                }
                opt.hidden = !!linkedJenis && opt.value !== linkedJenis;
            });
        }

        if (linkedJenis) {
            jenisSelect.value = linkedJenis;
        }
    }

    // Modal Tambah
    function openModalTambah() {
        const modal = document.getElementById('modalTambah');
        if (modal) modal.classList.remove('hidden');
    }
    function closeModalTambah() {
        const modal = document.getElementById('modalTambah');
        if (modal) modal.classList.add('hidden');
    }

    // Modal Edit
    function openModalEdit(data) {
        const form = document.getElementById('formEdit');
        if (!form) return;
        form.action = `/usulan-aspal/${data.id}`;

        const kabSelect = document.getElementById('edit_kabupaten_kota');
        kabSelect.querySelectorAll('option[data-temp="1"]').forEach(opt => opt.remove());
        if (data.kabupaten_kota && !Array.from(kabSelect.options).some(opt => opt.value === data.kabupaten_kota)) {
            const tempOpt = document.createElement('option');
            tempOpt.value = data.kabupaten_kota;
            tempOpt.dataset.temp = '1';
            tempOpt.textContent = data.kabupaten_kota;
            kabSelect.appendChild(tempOpt);
        }
        kabSelect.value = data.kabupaten_kota || '';

        document.getElementById('edit_jenis_pengusul_id').value = data.jenis_pengusul_id || '';
        syncPengusulByJenis('edit');
        document.getElementById('edit_pengusul_id').value = data.pengusul_id || '';
        document.getElementById('edit_qty_drum').value = data.qty_drum ?? '';
        document.getElementById('edit_keterangan_urgensi').value = data.keterangan_urgensi || '';

        const fileInfo = document.getElementById('edit_current_file_info');
        if (data.upload_surat_permohonan) {
            fileInfo.classList.remove('hidden');
            fileInfo.innerHTML = `
                <div class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px]">
                    <span class="truncate font-semibold">File saat ini: ${escapeHtml(data.upload_surat_permohonan)}</span>
                    <a href="/usulan-aspal/${encodeURIComponent(data.id)}/download-surat" class="font-bold underline flex-shrink-0 hover:text-emerald-950">Unduh</a>
                </div>
            `;
        } else {
            fileInfo.classList.add('hidden');
            fileInfo.innerHTML = '';
        }

        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeModalEdit() {
        const modal = document.getElementById('modalEdit');
        if (modal) modal.classList.add('hidden');
    }

    // Modal Detail
    function openModalDetail(data) {
        const body = document.getElementById('modalDetailBody');

        const jenisName = data.jenis_pengusul ? data.jenis_pengusul.nama_jenis_pengusul : '-';
        const pengusulName = data.pengusul ? data.pengusul.nama_pengusul : '-';
        const fileBlock = data.upload_surat_permohonan
            ? `<a href="/usulan-aspal/${encodeURIComponent(data.id)}/download-surat" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-bold text-xs transition">
                    <span>Unduh: ${escapeHtml(data.upload_surat_permohonan)}</span>
               </a>`
            : `<span class="text-slate-400 italic">Belum ada surat permohonan yang diunggah</span>`;

        body.innerHTML = `
            <div class="space-y-3">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">ID Usulan Aspal</span>
                        <span class="text-sm font-mono font-black text-[#0f2a71]">#${escapeHtml(String(data.id))}</span>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">${escapeHtml(jenisName)}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="p-3 bg-white border border-slate-200 rounded-xl">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Kabupaten / Kota</span>
                        <span class="font-bold text-slate-900">${escapeHtml(data.kabupaten_kota)}</span>
                    </div>
                    <div class="p-3 bg-white border border-slate-200 rounded-xl">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Pengusul</span>
                        <span class="font-bold text-slate-900">${escapeHtml(pengusulName)}</span>
                    </div>
                    <div class="p-3 bg-white border border-slate-200 rounded-xl">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Qty (Drum)</span>
                        <span class="font-bold text-amber-700 font-mono">${escapeHtml(Number(data.qty_drum || 0).toLocaleString('id-ID'))} Drum</span>
                    </div>
                </div>

                <div class="p-3.5 bg-white border border-slate-200 rounded-xl">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Keterangan Urgensi</span>
                    <p class="text-slate-800 whitespace-pre-line leading-relaxed">${escapeHtml(data.keterangan_urgensi)}</p>
                </div>

                <div class="p-3.5 bg-white border border-slate-200 rounded-xl">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1.5">Surat Permohonan</span>
                    <div>${fileBlock}</div>
                </div>

                <div class="text-[10px] text-slate-400 pt-1 flex justify-between border-t border-slate-100">
                    <span>Dibuat: ${data.created_at ? new Date(data.created_at).toLocaleString('id-ID') : '-'}</span>
                    <span>Update: ${data.updated_at ? new Date(data.updated_at).toLocaleString('id-ID') : '-'}</span>
                </div>
            </div>
        `;

        document.getElementById('modalDetail').classList.remove('hidden');
    }
    function closeModalDetail() {
        document.getElementById('modalDetail').classList.add('hidden');
    }

    // Modal Hapus
    function openModalHapus(id, kabKota) {
        const form = document.getElementById('formHapus');
        if (!form) return;
        form.action = `/usulan-aspal/${id}`;
        document.getElementById('hapusTextPrompt').innerHTML = `Apakah Anda yakin ingin menghapus usulan aspal untuk <strong>"${escapeHtml(kabKota)}"</strong>?`;
        document.getElementById('modalHapus').classList.remove('hidden');
    }
    function closeModalHapus() {
        const modal = document.getElementById('modalHapus');
        if (modal) modal.classList.add('hidden');
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModalTambah();
            closeModalEdit();
            closeModalDetail();
            closeModalHapus();
        }
    });
</script>
@endsection
