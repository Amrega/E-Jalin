<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Jalin Jatim - Integrasi Usulan & Monitoring Infrastruktur Jawa Timur</title>
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
                            50: '#f0f4fd',
                            100: '#e1e9fb',
                            200: '#c5d5f8',
                            300: '#9cbaf2',
                            400: '#6995ea',
                            500: '#4273df',
                            600: '#2756cc',
                            700: '#1d42a6',
                            800: '#1a3788',
                            900: '#0f2a71',
                            950: '#09183f',
                        },
                        gold: {
                            DEFAULT: '#ffb700',
                            50: '#fffbf0',
                            100: '#fff5d6',
                            200: '#ffebad',
                            300: '#ffdc7a',
                            400: '#ffca47',
                            500: '#ffb700',
                            600: '#e69d00',
                            700: '#b87500',
                            800: '#945b05',
                            900: '#7a4a0a',
                            950: '#472702',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen selection:bg-[#ffb700] selection:text-[#0f2a71]">

    <!-- Top Announcement Bar -->
    <div class="bg-[#0f2a71] text-white text-xs py-2 px-4 border-b border-[#ffb700]/30">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-[#ffb700] animate-pulse"></span>
                <span class="font-semibold text-slate-200">Dinas PU Bina Marga Provinsi Jawa Timur</span>
                <span class="hidden md:inline text-slate-400">•</span>
                <span class="hidden md:inline text-[#ffb700] font-medium">Ekosistem JALIN JATIM &amp; SiTIA</span>
            </div>
            <div class="flex items-center gap-4 text-[11px] text-slate-300">
                <span>Pelayanan Terpadu Usulan Kabupaten/Kota</span>
                <a href="#alur" class="text-[#ffb700] hover:underline font-semibold">Lihat Alur Tata Kelola &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand & Official Badges -->
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-[#0f2a71] border-2 border-[#ffb700] flex items-center justify-center text-[#ffb700] shadow-md shadow-[#0f2a71]/20 font-black text-2xl tracking-tighter shrink-0">
                        EJ
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-black tracking-tight text-[#0f2a71]">
                                E<span class="text-[#ffb700]">-JALIN</span>
                            </span>
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-[#0f2a71] text-[#ffb700] border border-[#ffb700]/40">
                                JATIM
                            </span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-500 tracking-tight leading-none">
                            Integrasi Usulan &amp; Monitoring Infrastruktur
                        </p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden lg:flex items-center space-x-7 text-sm font-semibold text-slate-700">
                    <a href="#beranda" class="text-[#0f2a71] font-bold border-b-2 border-[#ffb700] pb-1 transition">Beranda</a>
                    <a href="#alur" class="transition hover:text-[#0f2a71]">Alur Usulan</a>
                    <a href="#geospasial" class="transition hover:text-[#0f2a71]">Geospasial GIS</a>
                    <a href="#tata-kelola" class="transition hover:text-[#0f2a71]">Tata Kelola Bidang</a>
                    <a href="#upt" class="transition hover:text-[#0f2a71]">Distribusi UPT</a>
                </nav>

                <!-- Action Button -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm font-bold rounded-xl bg-[#0f2a71] text-[#ffb700] border border-[#ffb700]/40 hover:bg-[#1a3788] shadow-sm transition">
                            Dashboard ({{ Auth::user()->username }})
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4.5 py-2 text-xs sm:text-sm font-bold rounded-xl bg-[#ffb700] text-[#0f2a71] hover:bg-[#e69d00] shadow-md shadow-[#ffb700]/30 transition transform active:scale-95">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Masuk / Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <!-- Hero Section -->
        <section id="beranda" class="relative pt-12 pb-20 md:pt-20 md:pb-28 overflow-hidden bg-gradient-to-b from-white via-slate-50 to-slate-100">
            <!-- Background Glow Decorations -->
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[720px] h-[400px] bg-gradient-to-tr from-[#0f2a71]/15 via-[#ffb700]/20 to-[#0f2a71]/10 blur-3xl rounded-full pointer-events-none -z-10"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Ecosystem Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-[#0f2a71]/10 text-[#0f2a71] border border-[#ffb700]/50 mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#ffb700] ring-4 ring-[#ffb700]/30 animate-pulse"></span>
                    Sistem Digital Terpadu Pengelolaan Jalan &amp; Jembatan Jawa Timur
                </div>

                <!-- Main Title -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#0f2a71] tracking-tight leading-tight max-w-5xl mx-auto mb-6">
                    Tata Kelola Usulan &amp; Monitoring Infrastruktur Berbasis <span class="text-[#ffb700] underline decoration-[#0f2a71]/30 decoration-wavy underline-offset-8">Geospasial</span>
                </h1>

                <!-- Subtitle / Mission Statement -->
                <p class="text-base sm:text-lg text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed font-normal">
                    Mengintegrasikan ekosistem <strong>JALIN JATIM</strong> dan fungsi operasional <strong>SiTIA</strong> untuk mengubah kompilasi manual usulan Kabupaten, Kota, dan Instansi menjadi sistem digital yang transparan, akuntabel, serta tersaring secara objektif oleh Dinas PU Bina Marga Prov. Jatim.
                </p>

                <!-- CTA Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#alur" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 text-base font-bold rounded-xl bg-[#0f2a71] text-white hover:bg-[#1a3788] shadow-lg shadow-[#0f2a71]/30 transition transform active:scale-95">
                        Pelajari Alur Usulan
                        <svg class="w-5 h-5 ml-2 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <a href="#geospasial" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 text-base font-bold rounded-xl bg-white text-[#0f2a71] border-2 border-[#0f2a71]/20 hover:border-[#ffb700] hover:bg-[#fffbf0] shadow-sm transition">
                        <svg class="w-5 h-5 mr-2 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        Fitur Pemetaan GIS
                    </a>
                </div>

                <!-- Metric Highlights -->
                <div class="mt-16 max-w-5xl mx-auto">
                    <div class="bg-white rounded-2xl border-2 border-[#0f2a71]/15 shadow-xl shadow-slate-200/80 p-6 sm:p-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                        <div class="p-4 rounded-xl bg-[#0f2a71]/5 border border-[#0f2a71]/10">
                            <div class="text-3xl sm:text-4xl font-black text-[#0f2a71]">38</div>
                            <div class="text-xs sm:text-sm text-slate-600 mt-1 font-semibold">Kabupaten / Kota Terkoneksi</div>
                        </div>
                        <div class="p-4 rounded-xl bg-[#ffb700]/15 border border-[#ffb700]/40">
                            <div class="text-3xl sm:text-4xl font-black text-[#b87500]">100%</div>
                            <div class="text-xs sm:text-sm text-slate-600 mt-1 font-semibold">Penyaringan Objektif</div>
                        </div>
                        <div class="p-4 rounded-xl bg-[#0f2a71]/5 border border-[#0f2a71]/10">
                            <div class="text-3xl sm:text-4xl font-black text-[#0f2a71]">GIS</div>
                            <div class="text-xs sm:text-sm text-slate-600 mt-1 font-semibold">Presisi Berbasis Geospasial</div>
                        </div>
                        <div class="p-4 rounded-xl bg-[#ffb700]/15 border border-[#ffb700]/40">
                            <div class="text-3xl sm:text-4xl font-black text-[#b87500]">UPT</div>
                            <div class="text-xs sm:text-sm text-slate-600 mt-1 font-semibold">Distribusi Eksekusi Lapangan</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alur Tata Kelola Usulan (Workflow Section) -->
        <section id="alur" class="py-20 bg-white border-y border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block text-xs uppercase font-extrabold tracking-widest text-[#0f2a71] bg-[#ffb700]/25 px-3.5 py-1 rounded-full mb-3">
                        Alur Tata Kelola Transparan
                    </span>
                    <h2 class="text-3xl font-black text-[#0f2a71] tracking-tight sm:text-4xl">
                        Dari Usulan Manual Menuju Integrasi Digital Terpadu
                    </h2>
                    <p class="mt-4 text-slate-600 text-base">
                        Mekanisme penyaringan bertahap yang akuntabel guna memastikan setiap ruas prioritas ditangani secara tepat sasaran.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                    <!-- Step 1 -->
                    <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-200 hover:border-[#ffb700] hover:shadow-lg transition group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-[#0f2a71] text-[#ffb700] font-black text-xl flex items-center justify-center shadow-md">
                                01
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-blue-100 text-[#0f2a71]">Pengajuan</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0f2a71] mb-2">Usulan Kab/Kota/Instansi</h3>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Input data usulan infrastruktur jalan &amp; jembatan secara digital dilengkapi koordinat titik lokasi (geotagging) serta dokumentasi pendukung.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-200 hover:border-[#ffb700] hover:shadow-lg transition group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-[#ffb700] text-[#0f2a71] font-black text-xl flex items-center justify-center shadow-md">
                                02
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-amber-100 text-[#b87500]">Filtering</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0f2a71] mb-2">Penyaringan Bidang PU BM</h3>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Bidang teknis di Dinas PU Bina Marga Jatim menyaring usulan melalui parameter scoring objektif, kemantapan jalan, dan kelaikan fungsi.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-200 hover:border-[#ffb700] hover:shadow-lg transition group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-[#0f2a71] text-[#ffb700] font-black text-xl flex items-center justify-center shadow-md">
                                03
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-blue-100 text-[#0f2a71]">Disposisi</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0f2a71] mb-2">Distribusi ke UPT Wilayah</h3>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Paket usulan yang disetujui didistribusikan ke Unit Pelaksana Teknis (UPT) Pengelolaan Jalan dan Jembatan wilayah setempat.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-200 hover:border-[#ffb700] hover:shadow-lg transition group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-[#ffb700] text-[#0f2a71] font-black text-xl flex items-center justify-center shadow-md">
                                04
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Eksekusi</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0f2a71] mb-2">Eksekusi &amp; Monitoring GIS</h3>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                            Eksekusi fisik di lapangan dipantau secara berkala melalui modul geospasial real-time hingga tahap serah terima pekerjaan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fitur & Geospasial Section -->
        <section id="geospasial" class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="inline-block text-xs uppercase font-extrabold tracking-widest text-[#0f2a71] bg-[#ffb700]/25 px-3 py-1 rounded-full mb-3">
                            Teknologi Geospasial
                        </span>
                        <h2 class="text-3xl font-black text-[#0f2a71] tracking-tight sm:text-4xl mb-6">
                            Pemetaan Presisi &amp; Visualisasi Spasial Seluruh Ruas Jawa Timur
                        </h2>
                        <p class="text-slate-600 text-base leading-relaxed mb-8">
                            Aplikasi E Jalin mengadopsi integrasi GIS mutakhir yang memudahkan verifikator dan pelaksana lapangan dalam mengidentifikasi titik kerusakan, penanganan darurat, hingga progres konstruksi secara akurat.
                        </p>

                        <div class="space-y-4">
                            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-sm flex items-start gap-4">
                                <div class="w-9 h-9 rounded-lg bg-[#0f2a71] text-[#ffb700] flex items-center justify-center shrink-0 mt-0.5 font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-[#0f2a71] text-sm sm:text-base">Geotagging &amp; Validasi Koordinat Ruas</h4>
                                    <p class="text-xs text-slate-500 mt-1">Setiap usulan wajib menyertakan koordinat titik awal/akhir dan nomor ruas jalan provinsi untuk menghindari duplikasi.</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-sm flex items-start gap-4">
                                <div class="w-9 h-9 rounded-lg bg-[#0f2a71] text-[#ffb700] flex items-center justify-center shrink-0 mt-0.5 font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-[#0f2a71] text-sm sm:text-base">Parameter Scoring Otomatis</h4>
                                    <p class="text-xs text-slate-500 mt-1">Penilaian prioritas berbasis data teknis (IRAP/IRI, volume lalu lintas harian, dan konektivitas simpul ekonomi).</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-sm flex items-start gap-4">
                                <div class="w-9 h-9 rounded-lg bg-[#0f2a71] text-[#ffb700] flex items-center justify-center shrink-0 mt-0.5 font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-[#0f2a71] text-sm sm:text-base">Sinkronisasi JALIN JATIM &amp; SiTIA</h4>
                                    <p class="text-xs text-slate-500 mt-1">Saling bertukar data dengan ekosistem pelaporan jalan SiTIA dan master data perencanaan provinsi JALIN JATIM.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GIS Simulation Interactive Card -->
                    <div class="relative">
                        <div class="rounded-3xl bg-[#0f2a71] text-white p-6 sm:p-8 shadow-2xl border-2 border-[#ffb700]/30 overflow-hidden">
                            <!-- Header Mockup -->
                            <div class="flex items-center justify-between pb-5 border-b border-white/15 mb-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-3.5 h-3.5 rounded-full bg-[#ffb700]"></div>
                                    <div class="w-3.5 h-3.5 rounded-full bg-slate-400"></div>
                                    <div class="w-3.5 h-3.5 rounded-full bg-slate-400"></div>
                                </div>
                                <div class="text-[11px] font-mono font-bold text-[#ffb700] uppercase tracking-wider">
                                    GIS Live Spatial Layer
                                </div>
                            </div>

                            <!-- Map Graphic Mockup -->
                            <div class="relative h-56 rounded-2xl bg-[#09183f] border border-white/10 p-4 flex flex-col justify-between overflow-hidden">
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#ffb700_1px,transparent_1px)] [background-size:16px_16px]"></div>
                                
                                <div class="relative z-10 flex justify-between items-start">
                                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-black/40 text-[#ffb700] border border-[#ffb700]/30">Provinsi Jawa Timur</span>
                                    <span class="text-[10px] font-mono text-emerald-300 flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Live Tracking
                                    </span>
                                </div>

                                <!-- Waypoint Pins -->
                                <div class="relative z-10 flex justify-around items-center my-auto">
                                    <div class="text-center">
                                        <div class="w-8 h-8 rounded-full bg-[#ffb700] text-[#0f2a71] flex items-center justify-center font-bold text-xs mx-auto shadow-lg shadow-[#ffb700]/40">
                                            UPT
                                        </div>
                                        <span class="text-[10px] font-mono text-slate-300 mt-1 block">Surabaya</span>
                                    </div>
                                    <div class="h-0.5 flex-1 bg-gradient-to-r from-[#ffb700] to-emerald-400 mx-2"></div>
                                    <div class="text-center">
                                        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs mx-auto shadow-lg shadow-emerald-500/40">
                                            ✓
                                        </div>
                                        <span class="text-[10px] font-mono text-slate-300 mt-1 block">Malang</span>
                                    </div>
                                    <div class="h-0.5 flex-1 bg-gradient-to-r from-emerald-400 to-[#ffb700] mx-2"></div>
                                    <div class="text-center">
                                        <div class="w-8 h-8 rounded-full bg-[#ffb700] text-[#0f2a71] flex items-center justify-center font-bold text-xs mx-auto shadow-lg shadow-[#ffb700]/40">
                                            UPT
                                        </div>
                                        <span class="text-[10px] font-mono text-slate-300 mt-1 block">Madiun</span>
                                    </div>
                                </div>

                                <div class="relative z-10 text-[10px] font-mono text-slate-400 text-center">
                                    Koordinat: -7.2575° S, 112.7521° E • Multi-Layer WMS/WFS
                                </div>
                            </div>

                            <!-- Detail Statistics -->
                            <div class="mt-6 space-y-2.5 font-mono text-xs">
                                <div class="bg-black/25 p-3 rounded-xl border border-white/10 flex justify-between">
                                    <span class="text-slate-300">Status Validasi Spasial</span>
                                    <span class="text-[#ffb700] font-bold">Terverifikasi Bidang</span>
                                </div>
                                <div class="bg-black/25 p-3 rounded-xl border border-white/10 flex justify-between">
                                    <span class="text-slate-300">Pelaksana Lapangan</span>
                                    <span class="text-emerald-400 font-bold">UPT PJJ Terdisposisi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kategori Infrastruktur Section -->
        <section id="tata-kelola" class="py-20 bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="inline-block text-xs uppercase font-extrabold tracking-widest text-[#0f2a71] bg-[#ffb700]/25 px-3 py-1 rounded-full mb-3">
                        Cakupan Pekerjaan
                    </span>
                    <h2 class="text-3xl font-black text-[#0f2a71] tracking-tight sm:text-4xl">
                        Kategori Penanganan Infrastruktur Bina Marga
                    </h2>
                    <p class="mt-4 text-slate-600 text-base">
                        Sistem E Jalin memfasilitasi ragam jenis usulan konstruksi, pemeliharaan, serta penanganan kedaruratan.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Kategori 1 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border-2 border-slate-200 hover:border-[#ffb700] hover:shadow-xl transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-[#0f2a71] text-[#ffb700] flex items-center justify-center font-bold mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#0f2a71] mb-3">Pemeliharaan Berkala Jalan</h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-4">
                            Penanganan fungsional jalan provinsi, pelapisan ulang aspal (overlay), dan perbaikan bahu jalan guna mempertahankan kondisi mantap.
                        </p>
                        <div class="text-xs font-semibold text-[#0f2a71] flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#ffb700]"></span>
                            Disposisi Cepat UPT Daerah
                        </div>
                    </div>

                    <!-- Kategori 2 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border-2 border-slate-200 hover:border-[#ffb700] hover:shadow-xl transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-[#0f2a71] text-[#ffb700] flex items-center justify-center font-bold mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#0f2a71] mb-3">Rehabilitasi &amp; Jembatan</h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-4">
                            Penggantian bentang jembatan, pelebaran geometri, dan perkuatan struktur abutment jembatan di koridor vital Jawa Timur.
                        </p>
                        <div class="text-xs font-semibold text-[#0f2a71] flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#ffb700]"></span>
                            Audit Struktur Teknis
                        </div>
                    </div>

                    <!-- Kategori 3 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border-2 border-slate-200 hover:border-[#ffb700] hover:shadow-xl transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-[#0f2a71] text-[#ffb700] flex items-center justify-center font-bold mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#0f2a71] mb-3">Tanggap Darurat Bencana</h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-4">
                            Penanganan cepat longsor tebing jalan, jalan amblas, dan kerusakan akibat bencana hidrometeorologi dengan pelaporan terintegrasi SiTIA.
                        </p>
                        <div class="text-xs font-semibold text-[#0f2a71] flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#ffb700]"></span>
                            Prioritas Penanganan Mendesak
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="usulan-form" class="py-16 bg-slate-100 border-t border-slate-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-3xl bg-gradient-to-r from-[#0f2a71] to-[#1a3788] p-8 sm:p-12 text-center text-white shadow-2xl border-t-4 border-[#ffb700]">
                    <div class="inline-block px-3 py-1 rounded-full bg-[#ffb700]/20 text-[#ffb700] text-xs font-extrabold uppercase tracking-widest mb-4">
                        Tata Kelola Akuntabel
                    </div>
                    <h2 class="text-2xl sm:text-4xl font-black tracking-tight">
                        Integrasikan Usulan Daerah Anda Sekarang
                    </h2>
                    <p class="mt-4 text-slate-200 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                        Pemerintah Kabupaten, Kota, dan Instansi dapat mengakses portal resmi untuk input data, cek status penelaahan Bidang, hingga laporan eksekusi UPT.
                    </p>
                    
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-[#ffb700] text-[#0f2a71] font-black text-sm hover:bg-[#e69d00] transition shadow-lg shadow-[#ffb700]/30 transform active:scale-95">
                            Masuk ke Portal Usulan
                        </a>
                        <a href="http://localhost:8080" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 transition">
                            Akses phpMyAdmin
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer id="upt" class="bg-[#09183f] text-slate-400 text-xs sm:text-sm py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <!-- Col 1 -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-[#0f2a71] border border-[#ffb700] flex items-center justify-center text-[#ffb700] font-black text-lg">
                            EJ
                        </div>
                        <span class="text-white font-black text-xl tracking-tight">
                            E<span class="text-[#ffb700]">-JALIN</span> JATIM
                        </span>
                    </div>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed max-w-md">
                        Platform Integrasi Usulan dan Monitoring Infrastruktur Dinas PU Bina Marga Provinsi Jawa Timur. Menghubungkan aspirasi daerah dengan eksekusi lapangan secara presisi dan terdata.
                    </p>
                </div>

                <!-- Col 2 -->
                <div>
                    <h5 class="text-white font-bold text-sm mb-3">Unit Pelaksana Teknis (UPT)</h5>
                    <ul class="space-y-1.5 text-xs text-slate-400">
                        <li>UPT PJJ Surabaya &amp; Sidoarjo</li>
                        <li>UPT PJJ Mojokerto &amp; Jombang</li>
                        <li>UPT PJJ Malang &amp; Pasuruan</li>
                        <li>UPT PJJ Madiun &amp; Kediri</li>
                        <li>UPT PJJ Probolinggo &amp; Jember</li>
                        <li>UPT PJJ Banyuwangi &amp; Pamekasan</li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h5 class="text-white font-bold text-sm mb-3">Tautan Ekosistem</h5>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="#" class="hover:text-[#ffb700] transition">Portal JALIN JATIM</a></li>
                        <li><a href="#" class="hover:text-[#ffb700] transition">Aplikasi SiTIA Bina Marga</a></li>
                        <li><a href="http://localhost:8080" target="_blank" class="hover:text-[#ffb700] transition">phpMyAdmin Database</a></li>
                        <li><a href="http://localhost:8000" class="hover:text-[#ffb700] transition">Beranda E-Jalin</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <div>
                    &copy; 2026 Dinas PU Bina Marga Provinsi Jawa Timur. Hak Cipta Dilindungi.
                </div>
                <div class="text-[#ffb700] font-semibold">
                    Tata Kelola Transparan &bull; Akuntabel &bull; Geospasial
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
