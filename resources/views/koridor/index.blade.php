@extends('layouts.app')

@section('title', 'Referensi Koridor - E-Jalin Dinas PU Bina Marga Provinsi Jawa Timur')

@section('content')
    <!-- Breadcrumbs -->
    <nav class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 mb-6 gap-2">
        <a href="{{ url('/dashboard') }}" class="text-[#0f2a71] hover:underline">USULAN PEMDA</a>
        <span class="text-slate-400">&gt;</span>
        <span class="text-[#b87500] font-extrabold">REFERENSI KORIDOR</span>
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
            <button onclick="openModalTambah()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition transform hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Koridor</span>
            </button>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('koridor.index') }}" title="Refresh Halaman" class="p-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Table Container Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Filter Controls Form -->
        <form method="GET" action="{{ route('koridor.index') }}" class="p-4 sm:p-5 border-b border-slate-200 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 text-xs bg-slate-50/50">
            
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Per Page -->
                <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                    <select name="per_page" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-2.5 py-1.5 bg-white font-bold text-[#0f2a71] focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="hidden sm:inline">data</span>
                </div>

                <!-- Filter Kab/Kota -->
                <div class="flex items-center">
                    <select name="kabupaten_kota_id" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-3 py-1.5 bg-white font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0f2a71] max-w-xs">
                        <option value="">-- Semua Kab/Kota di Jatim --</option>
                        @foreach($kabupatenKotaList as $kk)
                            <option value="{{ $kk->id }}" {{ request('kabupaten_kota_id') == $kk->id ? 'selected' : '' }}>
                                {{ $kk->nama_kabupaten_kota }}
                            </option>
                        @endforeach
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

                @if(request()->anyFilled(['search', 'kabupaten_kota_id', 'status']))
                    <a href="{{ route('koridor.index') }}" class="px-2.5 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold transition flex items-center gap-1">
                        <span>Reset</span>
                        ✕
                    </a>
                @endif
            </div>

            <!-- Search Input -->
            <div class="flex items-center gap-2">
                <div class="relative w-full sm:w-72">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari nama / nomor koridor..."
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
                        <th class="py-3.5 px-4">NOMOR KORIDOR</th>
                        <th class="py-3.5 px-4">NAMA KORIDOR</th>
                        <th class="py-3.5 px-4">KABUPATEN / KOTA</th>
                        <th class="py-3.5 px-4 text-right">PANJANG (KM)</th>
                        <th class="py-3.5 px-4 text-center">FILE KML</th>
                        <th class="py-3.5 px-4 text-center">STATUS</th>
                        <th class="py-3.5 px-4 text-center w-36">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($koridors as $index => $item)
                        <tr class="hover:bg-blue-50/40 transition {{ $item->void ? 'bg-slate-50/70 text-slate-400' : '' }}">
                            <td class="py-3 px-4 text-center text-slate-400 font-mono">
                                {{ $koridors->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4">
                                @if($item->nomor_koridor)
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#0f2a71] border border-blue-200 font-mono font-bold text-xs inline-block">
                                        {{ $item->nomor_koridor }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900 {{ $item->void ? 'line-through text-slate-400' : '' }}">
                                {{ $item->nama_koridor }}
                            </td>
                            <td class="py-3 px-4 uppercase text-slate-600 font-semibold">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $item->kabupatenKota->nama_kabupaten_kota ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-[#0f2a71]">
                                @if(!is_null($item->panjang_koridor))
                                    {{ rtrim(rtrim(number_format($item->panjang_koridor, 4, ',', '.'), '0'), ',') }} <span class="text-[10px] text-slate-400 font-sans font-normal">km</span>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($item->file_kml_koridor)
                                    <a href="{{ route('koridor.download-kml', $item->id) }}" title="Download {{ $item->file_kml_koridor }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition font-bold text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        <span>KML</span>
                                    </a>
                                @else
                                    <span class="text-slate-300 italic text-[11px]">Belum ada</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('koridor.toggle-void', $item->id) }}" class="inline-block">
                                    @csrf
                                    @if(!$item->void)
                                        <button type="submit" title="Klik untuk non-aktifkan (Void)" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px] hover:bg-emerald-200 transition">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            <span>Aktif</span>
                                        </button>
                                    @else
                                        <button type="submit" title="Klik untuk aktifkan kembali" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 font-bold text-[11px] hover:bg-slate-300 transition">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Void</span>
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <button 
                                        type="button"
                                        onclick="openModalDetail({{ json_encode($item) }}, '{{ $item->kabupatenKota->nama_kabupaten_kota ?? '' }}')" 
                                        title="Lihat Detail" 
                                        class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 hover:bg-[#0f2a71] hover:text-[#ffb700] flex items-center justify-center transition shadow-2xs"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button 
                                        type="button"
                                        onclick="openModalEdit({{ json_encode($item) }})" 
                                        title="Ubah Koridor" 
                                        class="w-7 h-7 rounded-lg bg-[#0f2a71] text-white hover:bg-navy-700 flex items-center justify-center transition shadow-2xs"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <button 
                                        type="button"
                                        onclick="openModalHapus({{ $item->id }}, '{{ addslashes($item->nama_koridor) }}')" 
                                        title="Hapus Koridor" 
                                        class="w-7 h-7 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition shadow-2xs"
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                    </div>
                                    <span class="font-bold text-slate-600">Belum ada data koridor</span>
                                    <span class="text-xs text-slate-400">Silakan klik tombol <strong>+ Tambah KORIDOR</strong> di atas untuk menambahkan data baru.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        @if($koridors->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <div>
                    Menampilkan <span class="font-bold text-slate-800">{{ $koridors->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $koridors->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-800">{{ $koridors->total() }}</span> data
                </div>
                <div>
                    {{ $koridors->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- ============================================================== -->
    <!-- MODAL: TAMBAH KORIDOR                                         -->
    <!-- ============================================================== -->
    <div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Tambah Referensi Koridor</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Form Penambahan Data Koridor Jawa Timur</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalTambah()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                    ✕
                </button>
            </div>

            <form action="{{ route('koridor.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-grow overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 flex-grow">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">
                            Kabupaten / Kota <span class="text-red-500">*</span>
                        </label>
                        <select name="kabupaten_kota_id" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                            <option value="">-- Pilih Kabupaten / Kota --</option>
                            @foreach($kabupatenKotaList as $kk)
                                <option value="{{ $kk->id }}">{{ $kk->nama_kabupaten_kota }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Nomor / Kode Koridor</label>
                        <input type="text" name="nomor_koridor" placeholder="Contoh: K1, 1A, atau 01" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Nama Koridor <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_koridor" required placeholder="Contoh: Purabaya - Rajawali" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Panjang Koridor (KM)</label>
                        <input type="number" step="any" min="0" name="panjang_koridor" placeholder="Contoh: 21.50 atau 18.75432" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Format angka pecahan bebas (desimal tanpa batas koma).</span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">File KML Koridor (.kml)</label>
                        <input type="file" name="file_kml_koridor" accept=".kml,.xml,.kmz" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0f2a71] file:text-[#ffb700] hover:file:bg-navy-800 cursor-pointer">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">File spasial rute koridor (KML/KMZ) akan disimpan di folder <strong>uploads/kml/koridor</strong>. Maks 25 MB.</span>
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="void" value="0">
                            <input type="checkbox" name="void" value="1" class="w-4 h-4 rounded border-slate-300 text-[#0f2a71] focus:ring-[#0f2a71]">
                            <span class="font-bold text-slate-700">Tandai sebagai Void (Non-Aktif)</span>
                        </label>
                        <p class="text-[10px] text-slate-400 pl-6">Centang jika data koridor ini belum beroperasi / dinonaktifkan sementara.</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModalTambah()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition shadow-xs">Simpan Koridor</button>
                </div>
            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: EDIT KORIDOR                                           -->
    <!-- ============================================================== -->
    <div id="modalEdit" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Ubah Data Koridor</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Perbarui Informasi Referensi Koridor</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalEdit()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">✕</button>
            </div>

            <form id="formEdit" method="POST" enctype="multipart/form-data" class="flex flex-col flex-grow overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 flex-grow">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Kabupaten / Kota <span class="text-red-500">*</span></label>
                        <select id="edit_kabupaten_kota_id" name="kabupaten_kota_id" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                            <option value="">-- Pilih Kabupaten / Kota --</option>
                            @foreach($kabupatenKotaList as $kk)
                                <option value="{{ $kk->id }}">{{ $kk->nama_kabupaten_kota }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Nomor / Kode Koridor</label>
                        <input type="text" id="edit_nomor_koridor" name="nomor_koridor" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Nama Koridor <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_nama_koridor" name="nama_koridor" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Panjang Koridor (KM)</label>
                        <input type="number" step="any" min="0" id="edit_panjang_koridor" name="panjang_koridor" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71]">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Ganti File KML Koridor (.kml)</label>
                        <input type="file" name="file_kml_koridor" accept=".kml,.xml,.kmz" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#0f2a71] file:text-[#ffb700] hover:file:bg-navy-800 cursor-pointer">
                        <div id="edit_file_kml_info" class="text-[10px] text-slate-500 mt-1"></div>
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
                    <button type="button" onclick="closeModalEdit()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#0f2a71] hover:bg-navy-700 text-white font-bold transition shadow-xs">Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: DETAIL KORIDOR                                         -->
    <!-- ============================================================== -->
    <div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg max-h-[85vh] flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-8 h-8 rounded-full object-cover shadow-xs ring-1 ring-[#ffb700]">
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Detail Referensi Koridor</h3>
                        <p class="text-[10px] text-slate-300 font-medium">Informasi Lengkap Koridor Angkutan</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalDetail()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">✕</button>
            </div>

            <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 flex-grow" id="modalDetailBody"></div>

            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end">
                <button type="button" onclick="closeModalDetail()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition">Tutup</button>
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
                <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Data Koridor?</h3>
                <p class="text-xs text-slate-500 mb-4" id="hapusTextPrompt">
                    Apakah Anda yakin ingin menghapus koridor ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center justify-center gap-2.5">
                        <button type="button" onclick="closeModalHapus()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-100 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs transition shadow-xs">Ya, Hapus Data</button>
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
        form.action = `/koridor/${data.id}`;

        document.getElementById('edit_kabupaten_kota_id').value = data.kabupaten_kota_id;
        document.getElementById('edit_nomor_koridor').value = data.nomor_koridor || '';
        document.getElementById('edit_nama_koridor').value = data.nama_koridor || '';
        document.getElementById('edit_panjang_koridor').value = data.panjang_koridor !== null ? data.panjang_koridor : '';
        document.getElementById('edit_void').checked = data.void == 1;

        const kmlInfo = document.getElementById('edit_file_kml_info');
        if (data.file_kml_koridor) {
            kmlInfo.innerHTML = `<span class="text-slate-600 font-semibold">File saat ini:</span> <span class="font-mono text-emerald-700">${data.file_kml_koridor}</span> (biarkan kosong jika tidak ingin mengubah)`;
        } else {
            kmlInfo.innerHTML = `<span class="text-slate-400 italic">Belum ada file KML yang diupload</span>`;
        }

        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeModalEdit() {
        document.getElementById('modalEdit').classList.add('hidden');
    }

    // Modal Detail
    function openModalDetail(data, namaKabKota) {
        const body = document.getElementById('modalDetailBody');
        const kmlDownloadLink = data.file_kml_koridor 
            ? `<a href="/koridor/${data.id}/download-kml" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Download File KML</span>
               </a>`
            : `<span class="text-slate-400 italic">Tidak tersedia</span>`;

        const statusBadge = data.void == 1 
            ? `<span class="px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 font-bold">Void / Non-Aktif</span>`
            : `<span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold">Aktif / Beroperasi</span>`;

        body.innerHTML = `
            <div class="space-y-3">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Nomor Koridor</span>
                        <span class="text-sm font-mono font-black text-[#0f2a71]">${data.nomor_koridor || '-'}</span>
                    </div>
                    <div>${statusBadge}</div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-white border border-slate-200 rounded-xl">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Kabupaten / Kota</span>
                        <span class="font-bold text-slate-800">${namaKabKota || '-'}</span>
                    </div>
                    <div class="p-3 bg-white border border-slate-200 rounded-xl">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Panjang Koridor</span>
                        <span class="font-bold text-[#0f2a71]">${data.panjang_koridor ? data.panjang_koridor + ' KM' : '-'}</span>
                    </div>
                </div>

                <div class="p-3.5 bg-white border border-slate-200 rounded-xl">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Nama Koridor</span>
                    <span class="font-bold text-slate-900 text-sm leading-snug">${data.nama_koridor}</span>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">File Spasial KML</span>
                        <span class="font-mono text-slate-700 font-semibold">${data.file_kml_koridor || 'Belum ada'}</span>
                    </div>
                    <div>${kmlDownloadLink}</div>
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
    function openModalHapus(id, namaKoridor) {
        const form = document.getElementById('formHapus');
        form.action = `/koridor/${id}`;
        document.getElementById('hapusTextPrompt').innerHTML = `Apakah Anda yakin ingin menghapus koridor <strong>"${namaKoridor}"</strong>? File KML terkait juga akan dihapus.`;
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
