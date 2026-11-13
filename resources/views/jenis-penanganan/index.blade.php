@extends('layouts.app')

@section('title', 'Referensi Jenis Penanganan - E-Jalin Dinas PU Bina Marga Provinsi Jawa Timur')

@section('content')
    <!-- Breadcrumbs -->
    <nav class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 mb-6 gap-2">
        <span class="text-[#0f2a71] hover:underline cursor-pointer">REFERENSI MASTER</span>
        <span class="text-slate-400">&gt;</span>
        <span class="text-[#b87500] font-extrabold">JENIS PENANGANAN</span>
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
            <svg class="w-5 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <span>Tambah Jenis Penanganan</span>
            </button>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('jenis-penanganan.index') }}" title="Refresh Halaman" class="p-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Table Container Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Filter Controls Form -->
        <form method="GET" action="{{ route('jenis-penanganan.index') }}" class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 text-xs bg-slate-50/50">
            
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Per Page -->
                <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                    <select name="per_page" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-2.5 py-1.5 bg-white font-bold text-[#0f2a71] focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span>data</span>
                </div>

                <!-- Filter Target Penanganan (jalan / jembatan) -->
                <div class="flex items-center">
                    <select name="target_penangan" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-3 py-1.5 bg-white font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                        <option value="">-- Semua Target Penanganan --</option>
                        <option value="jalan" {{ (request('target_penangan') === 'jalan' || request('target_penanganan') === 'jalan') ? 'selected' : '' }}>Jalan</option>
                        <option value="jembatan" {{ (request('target_penangan') === 'jembatan' || request('target_penanganan') === 'jembatan') ? 'selected' : '' }}>Jembatan</option>
                    </select>
                </div>

                <!-- Filter Status Void -->
                <div class="flex items-center">
                    <select name="status" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-3 py-1.5 bg-white font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                        <option value="void" {{ request('status') === 'void' ? 'selected' : '' }}>Hanya Void</option>
                    </select>
                </div>

                @if(request()->anyFilled(['search', 'target_penangan', 'target_penanganan', 'status']))
                    <a href="{{ route('jenis-penanganan.index') }}" class="px-2.5 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold transition flex items-center gap-1">
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
                        placeholder="Cari jenis penanganan..."
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
                        <th class="py-3.5 px-4 text-center w-40">TARGET PENANGANAN</th>
                        <th class="py-3.5 px-4">NAMA JENIS PENANGANAN</th>
                        <th class="py-3.5 px-4 text-center">STATUS</th>
                        <th class="py-3.5 px-4">TANGGAL DIBUAT</th>
                        <th class="py-3.5 px-4">TERAKHIR DIUBAH</th>
                        <th class="py-3.5 px-4 text-center w-36">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($jenisPenangananList as $index => $item)
                        <tr class="hover:bg-blue-50/40 transition {{ $item->void ? 'bg-slate-50/70 text-slate-400' : '' }}">
                            <td class="py-3 px-4 text-center text-slate-400 font-mono">
                                {{ $jenisPenangananList->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($item->target_penangan === 'jalan')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-800 border border-blue-200 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        <span>Jalan</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                        <span>Jembatan</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900 {{ $item->void ? 'line-through text-slate-400' : '' }}">
                                    {{ $item->nama_penanganan }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('jenis-penanganan.toggle-void', $item->id) }}" class="inline-block">
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
                                        onclick="openModalDetail({{ json_encode($item) }})" 
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
                                        onclick="openModalEdit({{ json_encode($item) }})" 
                                        title="Ubah Penanganan" 
                                        class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-[#1a3d99] flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <button 
                                        type="button"
                                        onclick="openModalHapus({{ $item->id }}, '{{ addslashes($item->nama_penanganan) }}')" 
                                        title="Hapus Penanganan" 
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
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <span class="font-bold text-slate-600">Belum ada data jenis penanganan</span>
                                    <span class="text-xs text-slate-400">Silakan klik tombol <strong>+ Tambah Jenis Penanganan</strong> di atas untuk menambahkan data baru.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        @if($jenisPenangananList->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <div>
                    Menampilkan <span class="font-bold text-slate-800">{{ $jenisPenangananList->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $jenisPenangananList->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-800">{{ $jenisPenangananList->total() }}</span> data
                </div>
                <div>
                    {{ $jenisPenangananList->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- ============================================================== -->
    <!-- MODAL: TAMBAH JENIS PENANGANAN                                -->
    <!-- ============================================================== -->
    <div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Tambah Jenis Penanganan</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Registrasi Master Penanganan Infrastruktur</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalTambah()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition cursor-pointer">
                    ✕
                </button>
            </div>

            <form action="{{ route('jenis-penanganan.store') }}" method="POST" class="flex flex-col flex-grow">
                @csrf
                <div class="p-6 space-y-4 text-xs text-slate-700 flex-grow">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Target Penanganan <span class="text-red-500">*</span>
                        </label>
                        <select name="target_penangan" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                            <option value="">-- Pilih Target Penanganan --</option>
                            <option value="jalan">Jalan</option>
                            <option value="jembatan">Jembatan</option>
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Pilih apakah penanganan ini diperuntukkan untuk ruas Jalan atau Jembatan.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Nama Kegiatan / Jenis Penanganan <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nama_penanganan" 
                            required 
                            placeholder="Contoh: Pemeliharaan Rutin Jalan / Penggantian Jembatan"
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]"
                        >
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="void" value="0">
                            <input type="checkbox" name="void" value="1" class="w-4 h-4 rounded border-slate-300 text-[#0f2a71] focus:ring-[#0f2a71]">
                            <span class="font-bold text-slate-700">Tandai sebagai Void (Non-Aktif)</span>
                        </label>
                        <p class="text-[10px] text-slate-400 pl-6">Centang jika jenis penanganan ini dinonaktifkan sementara.</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalTambah()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition shadow-xs cursor-pointer">Simpan Jenis Penanganan</button>
                </div>
            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: EDIT JENIS PENANGANAN                                  -->
    <!-- ============================================================== -->
    <div id="modalEdit" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Ubah Jenis Penanganan</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Perbarui Data Jenis Penanganan Infrastruktur</p>
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
                            Target Penanganan <span class="text-red-500">*</span>
                        </label>
                        <select id="edit_target_penangan" name="target_penangan" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                            <option value="jalan">Jalan</option>
                            <option value="jembatan">Jembatan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Nama Kegiatan / Jenis Penanganan <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="edit_nama_penanganan" 
                            name="nama_penanganan" 
                            required 
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]"
                        >
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
    <!-- MODAL: DETAIL JENIS PENANGANAN                                -->
    <!-- ============================================================== -->
    <div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Detail Jenis Penanganan</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Informasi Kategori Master Penanganan</p>
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
                <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Jenis Penanganan?</h3>
                <p class="text-xs text-slate-500 mb-4" id="hapusTextPrompt">
                    Apakah Anda yakin ingin menghapus jenis penanganan ini? Tindakan ini tidak dapat dibatalkan.
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
        form.action = `/jenis-penanganan/${data.id}`;

        const targetVal = data.target_penangan || data.target_penanganan || 'jalan';
        document.getElementById('edit_target_penangan').value = targetVal;
        document.getElementById('edit_nama_penanganan').value = data.nama_penanganan || '';
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

        const targetVal = data.target_penangan || data.target_penanganan || 'jalan';
        const targetBadge = targetVal === 'jalan'
            ? `<span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold">Infrastruktur Jalan</span>`
            : `<span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold">Infrastruktur Jembatan</span>`;

        body.innerHTML = `
            <div class="space-y-3">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">ID Jenis Penanganan</span>
                        <span class="text-sm font-mono font-black text-[#0f2a71]">#${data.id}</span>
                    </div>
                    <div>${statusBadge}</div>
                </div>

                <div class="p-3 bg-white border border-slate-200 rounded-xl">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Target Penanganan</span>
                    <div>${targetBadge}</div>
                </div>

                <div class="p-3.5 bg-white border border-slate-200 rounded-xl">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Nama Jenis Penanganan</span>
                    <span class="font-bold text-slate-900 text-sm leading-snug">${data.nama_penanganan}</span>
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
    function openModalHapus(id, namaPenanganan) {
        const form = document.getElementById('formHapus');
        form.action = `/jenis-penanganan/${id}`;
        document.getElementById('hapusTextPrompt').innerHTML = `Apakah Anda yakin ingin menghapus jenis penanganan <strong>"${namaPenanganan}"</strong>?`;
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
