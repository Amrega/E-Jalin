@extends('layouts.app')

@section('title', 'Daftar UPT PJJ - E-Jalin Dinas PU Bina Marga Provinsi Jawa Timur')

@section('content')
    <!-- Breadcrumbs -->
    <nav class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 mb-6 gap-2">
        <span class="text-[#0f2a71] hover:underline cursor-pointer">REFERENSI MASTER</span>
        <span class="text-slate-400">&gt;</span>
        <span class="text-[#b87500] font-extrabold">DAFTAR UPT PJJ</span>
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

    <!-- Top Action Header & Buttons -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <button onclick="openModalTambah()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah UPT PJJ</span>
            </button>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('upt-dinas.index') }}" title="Refresh Halaman" class="p-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Table Container Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Filter Controls Form -->
        <form method="GET" action="{{ route('upt-dinas.index') }}" class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 text-xs bg-slate-50/50">
            
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Per Page -->
                <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                    <select name="per_page" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-2.5 py-1.5 bg-white font-bold text-[#0f2a71] focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 (Default)</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span>data</span>
                </div>

                <!-- Filter Status Void -->
                <div class="flex items-center">
                    <select name="status" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-3 py-1.5 bg-white font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                        <option value="void" {{ request('status') === 'void' ? 'selected' : '' }}>Hanya Void</option>
                    </select>
                </div>

                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('upt-dinas.index') }}" class="px-2.5 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold transition flex items-center gap-1">
                        <span>Reset</span>
                        ✕
                    </a>
                @endif
            </div>

            <!-- Search Input -->
            <div class="flex items-center gap-2">
                <div class="relative w-full sm:w-80">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari nama UPT PJJ..."
                        class="w-full border border-slate-300 rounded-lg pl-3 pr-8 py-1.5 bg-white text-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                    >
                    <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#0f2a71]">
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
                        <th class="py-3.5 px-4">NAMA UPT PJJ</th>
                        <th class="py-3.5 px-4">PROVINSI</th>
                        <th class="py-3.5 px-4">WILAYAH KERJA / NAUNGAN KABUPATEN-KOTA</th>
                        <th class="py-3.5 px-4 text-center">STATUS</th>
                        <th class="py-3.5 px-4">TANGGAL DIBUAT</th>
                        <th class="py-3.5 px-4">TERAKHIR DIUBAH</th>
                        <th class="py-3.5 px-4 text-center w-36">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($uptList as $index => $item)
                        <tr class="hover:bg-blue-50/40 transition {{ $item->void ? 'bg-slate-50/70 text-slate-400' : '' }}">
                            <td class="py-3 px-4 text-center text-slate-400 font-mono">
                                {{ $uptList->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900 text-sm {{ $item->void ? 'line-through text-slate-400' : '' }}">
                                    {{ $item->nama_upt }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <span class="font-semibold">{{ $item->provinsi ? $item->provinsi->nama_provinsi : 'Jawa Timur' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex flex-wrap items-center gap-1.5 max-w-lg">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#0f2a71]/10 text-[#0f2a71] border border-[#0f2a71]/20">
                                        {{ $item->kabupaten_kota_count }} Wilayah
                                    </span>
                                    @foreach($item->kabupatenKota->take(3) as $kab)
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] border border-slate-200">
                                            {{ str_replace(['Kabupaten ', 'Kota '], '', $kab->nama_kabupaten_kota) }}
                                        </span>
                                    @endforeach
                                    @if($item->kabupaten_kota_count > 3)
                                        <span class="text-[10px] text-slate-400 font-bold" title="Lihat detail untuk daftar lengkap">
                                            +{{ $item->kabupaten_kota_count - 3 }} lainnya
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('upt-dinas.toggle-void', $item->id) }}" class="inline-block">
                                    @csrf
                                    @if(!$item->void)
                                        <button type="submit" title="Klik untuk non-aktifkan (Void)" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px] hover:bg-emerald-200 transition cursor-pointer">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            <span>Aktif</span>
                                        </button>
                                    @else
                                        <button type="submit" title="Klik untuk aktifkan kembali" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 font-bold text-[11px] hover:bg-slate-300 transition cursor-pointer">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Void</span>
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="py-3 px-4 text-slate-500 text-[11px]">
                                {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-slate-500 text-[11px]">
                                {{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}
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
                                    <button 
                                        type="button"
                                        onclick='openModalEdit(@json($item))' 
                                        title="Ubah UPT PJJ" 
                                        class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3d99] flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <button 
                                        type="button"
                                        onclick="openModalHapus({{ $item->id }}, '{{ addslashes($item->nama_upt) }}', {{ $item->kabupaten_kota_count }})" 
                                        title="Hapus UPT PJJ" 
                                        class="w-7 h-7 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <span class="font-bold text-slate-600">Belum ada data UPT PJJ</span>
                                    <span class="text-xs text-slate-400">Silakan klik tombol <strong>+ Tambah UPT PJJ</strong> di atas untuk menambahkan data baru.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        @if($uptList->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <div>
                    Menampilkan <span class="font-bold text-slate-800">{{ $uptList->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $uptList->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-800">{{ $uptList->total() }}</span> data
                </div>
                <div>
                    {{ $uptList->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- ============================================================== -->
    <!-- MODAL: TAMBAH UPT PJJ                                         -->
    <!-- ============================================================== -->
    <div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Tambah UPT PJJ</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Registrasi Unit Pengelolaan Jalan & Jembatan</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalTambah()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition cursor-pointer">
                    ✕
                </button>
            </div>

            <form action="{{ route('upt-dinas.store') }}" method="POST" class="flex flex-col flex-grow">
                @csrf
                <div class="p-6 space-y-4 text-xs text-slate-700 flex-grow">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Nama UPT PJJ <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nama_upt" 
                            required 
                            placeholder="Contoh: UPT PJJ Surabaya / UPT PJJ Malang"
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]"
                        >
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <select name="provinsi_id" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                            @foreach($provinsiList as $prov)
                                <option value="{{ $prov->id }}" {{ $prov->id == 1 ? 'selected' : '' }}>
                                    {{ $prov->nama_provinsi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="void" value="0">
                            <input type="checkbox" name="void" value="1" class="w-4 h-4 rounded border-slate-300 text-[#0f2a71] focus:ring-[#0f2a71]">
                            <span class="font-bold text-slate-700">Tandai sebagai Void (Non-Aktif)</span>
                        </label>
                        <p class="text-[10px] text-slate-400 pl-6">Centang jika kantor UPT ini dinonaktifkan sementara dari referensi.</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalTambah()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition shadow-xs cursor-pointer">Simpan UPT PJJ</button>
                </div>
            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: EDIT UPT PJJ                                           -->
    <!-- ============================================================== -->
    <div id="modalEdit" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Ubah Data UPT PJJ</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Perbarui Informasi Kantor Unit Pengelolaan</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalEdit()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition cursor-pointer">✕</button>
            </div>

            <form id="formEdit" method="POST" class="flex flex-col flex-grow">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4 text-xs text-slate-700 flex-grow">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Nama UPT PJJ <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="edit_nama_upt" 
                            name="nama_upt" 
                            required 
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]"
                        >
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <select id="edit_provinsi_id" name="provinsi_id" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                            @foreach($provinsiList as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->nama_provinsi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="void" value="0">
                            <input type="checkbox" id="edit_void" name="void" value="1" class="w-4 h-4 rounded border-slate-300 text-[#0f2a71] focus:ring-[#0f2a71]">
                            <span class="font-bold text-slate-700">Tandai sebagai Void (Non-Aktif)</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalEdit()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#0f2a71] hover:bg-navy-700 text-white font-bold transition shadow-xs cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: DETAIL UPT PJJ                                         -->
    <!-- ============================================================== -->
    <div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Detail UPT PJJ</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Informasi Wilayah Kerja Pengelolaan Jalan & Jembatan</p>
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
                <h3 class="text-base font-bold text-slate-800 mb-1">Hapus UPT PJJ?</h3>
                <p class="text-xs text-slate-500 mb-3" id="hapusTextPrompt">
                    Apakah Anda yakin ingin menghapus data UPT PJJ ini?
                </p>
                <div id="hapusWarningRelations" class="mb-4 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] text-left hidden">
                    ⚠️ <strong>Peringatan Relasi Wilayah:</strong> UPT PJJ ini masih menaungi Kabupaten / Kota. Sistem akan menolak penghapusan permanen demi menjaga keutuhan data. Silakan gunakan opsi <strong>Non-Aktifkan (Void)</strong>.
                </div>
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
@endsection

@section('scripts')
<script>
    // Modal Tambah
    function openModalTambah() {
        document.getElementById('modalTambah').classList.remove('hidden');
    }
    function closeModalTambah() {
        document.getElementById('modalTambah').classList.add('hidden');
    }

    // Modal Edit
    function openModalEdit(data) {
        const form = document.getElementById('formEdit');
        form.action = `/upt-dinas/${data.id}`;

        document.getElementById('edit_nama_upt').value = data.nama_upt || '';
        document.getElementById('edit_provinsi_id').value = data.provinsi_id || 1;
        document.getElementById('edit_void').checked = (data.void == 1 || data.void === true);

        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeModalEdit() {
        document.getElementById('modalEdit').classList.add('hidden');
    }

    // Modal Detail
    function openModalDetail(data) {
        const body = document.getElementById('modalDetailBody');

        const statusBadge = (data.void == 1 || data.void === true)
            ? `<span class="px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 font-bold">Void / Non-Aktif</span>`
            : `<span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold">Aktif</span>`;

        const provNama = data.provinsi ? data.provinsi.nama_provinsi : 'Jawa Timur';
        const kabList = data.kabupaten_kota || [];
        const kabCount = data.kabupaten_kota_count || kabList.length || 0;

        let listHtml = '';
        if (kabList.length > 0) {
            listHtml = kabList.map(k => `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-800 font-semibold text-[11px] shadow-2xs">
                    📍 ${k.nama_kabupaten_kota}
                </span>
            `).join('');
        } else {
            listHtml = '<span class="text-slate-400 italic text-[11px]">Belum ada wilayah kabupaten/kota yang dipetakan ke UPT ini.</span>';
        }

        body.innerHTML = `
            <div class="space-y-3">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">ID UPT PJJ</span>
                        <span class="text-sm font-mono font-black text-[#0f2a71]">#${data.id}</span>
                    </div>
                    <div>${statusBadge}</div>
                </div>

                <div class="p-3.5 bg-white border border-slate-200 rounded-xl">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Nama UPT PJJ</span>
                    <span class="font-bold text-slate-900 text-base leading-snug">${data.nama_upt}</span>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Provinsi ${provNama}</span>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] uppercase font-bold text-slate-600 block">Wilayah Naungan Administrasi</span>
                        <span class="px-2 py-0.5 rounded-full bg-[#0f2a71] text-[#ffb700] font-black text-[10px]">${kabCount} Kab / Kota</span>
                    </div>
                    <div class="flex flex-wrap gap-1.5 pt-1 max-h-48 overflow-y-auto custom-scrollbar">
                        ${listHtml}
                    </div>
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
    function openModalHapus(id, nama, kabCount) {
        const form = document.getElementById('formHapus');
        form.action = `/upt-dinas/${id}`;
        document.getElementById('hapusTextPrompt').innerHTML = `Apakah Anda yakin ingin menghapus data <strong>"${nama}"</strong>?`;
        
        const warning = document.getElementById('hapusWarningRelations');
        if (kabCount > 0) {
            warning.classList.remove('hidden');
            warning.innerHTML = `⚠️ <strong>Peringatan Relasi Wilayah:</strong> UPT PJJ ini masih menaungi <strong>${kabCount} Kabupaten / Kota</strong>. Sistem akan menolak penghapusan permanen untuk menjaga relasi database. Silakan gunakan tombol <strong>Non-Aktifkan (Void)</strong> jika kantor ini tidak aktif lagi.`;
        } else {
            warning.classList.add('hidden');
        }

        document.getElementById('modalHapus').classList.remove('hidden');
    }
    function closeModalHapus() {
        document.getElementById('modalHapus').classList.add('hidden');
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
