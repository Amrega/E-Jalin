<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Jalin Jatim - Portal Integrasi Usulan & Monitoring Infrastruktur</title>
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
                            900: '#0f2a71',
                            950: '#09183f',
                        },
                        gold: {
                            DEFAULT: '#ffb700',
                            600: '#e69d00',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased font-sans min-h-screen relative overflow-x-hidden selection:bg-[#ffb700] selection:text-[#0f2a71]">

    <!-- Full-Screen Background Image -->
    <div class="fixed inset-0 -z-20 bg-cover bg-center bg-no-repeat scale-105 transition-transform duration-1000"
         style="background-image: url('{{ asset('assets/background_langingpage.jpeg') }}');">
    </div>

    <!-- Gradient Overlay agar gambar tetap jelas sekaligus teks & form kontras -->
    <div class="fixed inset-0 -z-10 bg-gradient-to-r from-[#06102b]/85 via-[#09183f]/50 to-[#06102b]/80"></div>
    <div class="fixed inset-0 -z-10 bg-gradient-to-t from-[#050c21]/80 via-transparent to-[#050c21]/40"></div>

    <!-- Main Wrapper -->
    <div class="min-h-screen flex flex-col justify-between px-4 sm:px-8 lg:px-14 py-6">

        <!-- Top Brand Bar -->
        <header class="w-full max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3.5 group">
                <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover shadow-lg ring-2 ring-[#ffb700]/60">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl sm:text-3xl font-black tracking-tight text-white drop-shadow">
                            E<span class="text-[#ffb700]">-JALIN</span>
                        </span>
                        <span class="hidden sm:inline-block text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-0.5 rounded-full bg-[#ffb700]/20 text-[#ffb700] border border-[#ffb700]/40">
                            JATIM
                        </span>
                    </div>
                    <span class="text-[11px] sm:text-xs font-semibold text-slate-300 block tracking-wide">
                        Dinas PU Bina Marga Provinsi Jawa Timur
                    </span>
                </div>
            </a>

            <!-- <div class="hidden md:flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs text-slate-200">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Ekosistem Digital <strong class="text-[#ffb700]">JALIN JATIM &amp; SiTIA</strong></span>
            </div> -->
        </header>

        <!-- Center Content: Left Hero Info & Right Login Box -->
        <main class="w-full max-w-7xl mx-auto my-auto py-8 lg:py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">

                <!-- Left Side: Information & Highlights -->
                <div class="lg:col-span-7 text-white space-y-6 text-center lg:text-left">
                    <!-- <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-[#ffb700]/15 text-[#ffb700] border border-[#ffb700]/40 backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-[#ffb700] animate-ping"></span>
                        Sistem Digital Terpadu Jalan &amp; Jembatan Jawa Timur
                    </div> -->

                    <h1 class="text-3xl sm:text-5xl xl:text-[54px] font-black tracking-tight leading-[1.12] drop-shadow-md">
                        Jejaring Akselerasi Layanan Infrastruktur Jalan Daerah Jawa Timur
                    </h1>

                    <p class="text-sm sm:text-base text-slate-200/90 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                        Transformasi Kebijakan dan Tata Kelola Pembangunan Jalan Daerah Berkeadilan di Jawa Timur
                    </p>

                    <!-- Feature Highlights -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2 max-w-2xl mx-auto lg:mx-0">
                        <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-left hover:bg-white/15 transition">
                            <div class="w-9 h-9 rounded-xl bg-[#ffb700]/20 border border-[#ffb700]/40 flex items-center justify-center text-[#ffb700] mb-2.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-white">Peta Spasial GIS</h3>
                            <p class="text-xs text-slate-300 mt-0.5 leading-snug">Visualisasi koridor, ruas jalan, dan jembatan berbasis KML.</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-left hover:bg-white/15 transition">
                            <div class="w-9 h-9 rounded-xl bg-[#ffb700]/20 border border-[#ffb700]/40 flex items-center justify-center text-[#ffb700] mb-2.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-white">Verifikasi Terpadu</h3>
                            <p class="text-xs text-slate-300 mt-0.5 leading-snug">Sinkronisasi usulan Pemda, Bappeda, dan UPT PJJ Jawa Timur.</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-left hover:bg-white/15 transition">
                            <div class="w-9 h-9 rounded-xl bg-[#ffb700]/20 border border-[#ffb700]/40 flex items-center justify-center text-[#ffb700] mb-2.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-white">38 Kab/Kota &amp; 11 UPT</h3>
                            <p class="text-xs text-slate-300 mt-0.5 leading-snug">Cakupan penuh infrastruktur jalan &amp; jembatan daerah.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Login Panel -->
                <div class="lg:col-span-5 w-full max-w-md mx-auto lg:ml-auto lg:mr-0">
                    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/40 border border-white/40 overflow-hidden">

                        <!-- Login Header Banner -->
                        <div class="bg-gradient-to-br from-[#0f2a71] to-[#09183f] px-7 pt-7 pb-6 text-center relative overflow-hidden border-b-4 border-[#ffb700]">
                            <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#ffb700]/20 rounded-full blur-2xl pointer-events-none"></div>
                            <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-blue-400/20 rounded-full blur-2xl pointer-events-none"></div>

                            <img src="{{ asset('assets/logo_ejalin.png') }}" alt="Logo E-Jalin" class="w-16 h-16 mx-auto mb-2.5 rounded-full object-cover shadow-md ring-2 ring-[#ffb700]">
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">Portal Masuk E-Jalin</h2>
                            <p class="text-xs text-slate-300 mt-1">Silakan masuk menggunakan akun resmi Anda</p>
                        </div>

                        <!-- Form Body -->
                        <div class="p-7 sm:p-8">
                            @if (session('status'))
                                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ session('status') }}</span>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold flex items-start gap-2">
                                    <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                @csrf

                                <!-- Username / Email -->
                                <div>
                                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-[#0f2a71] mb-1.5">
                                        Username / Email
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <input
                                            id="username"
                                            type="text"
                                            name="username"
                                            value="{{ old('username') }}"
                                            required
                                            autofocus
                                            placeholder="Masukkan username atau email"
                                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 bg-slate-50 text-slate-900 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                                        >
                                    </div>
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#0f2a71] mb-1.5">
                                        Password
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            required
                                            placeholder="Masukkan password"
                                            class="w-full pl-11 pr-11 py-3 rounded-xl border border-slate-300 bg-slate-50 text-slate-900 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                                        >
                                        <button
                                            type="button"
                                            onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';"
                                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#0f2a71] transition"
                                            title="Tampilkan/Sembunyikan Password"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Remember Me -->
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-600 pt-1">
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#0f2a71] border-slate-300 focus:ring-[#0f2a71]">
                                        <span>Ingat Saya di Perangkat Ini</span>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <button
                                    type="submit"
                                    class="w-full py-3.5 px-4 rounded-xl bg-[#ffb700] text-[#0f2a71] font-black text-sm tracking-wide uppercase hover:bg-[#e69d00] shadow-lg shadow-[#ffb700]/30 transition transform active:scale-[0.98] flex items-center justify-center gap-2"
                                >
                                    <span>Masuk ke Sistem</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full max-w-7xl mx-auto pt-4 border-t border-white/15 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-300">
            <span>&copy; {{ date('Y') }} Dinas PU Bina Marga Provinsi Jawa Timur. Hak Cipta Dilindungi.</span>
            <span class="text-slate-400">E-Jalin v1.0 &bull; Sistem Informasi Geospasial Jalan &amp; Jembatan</span>
        </footer>

    </div>

</body>
</html>
