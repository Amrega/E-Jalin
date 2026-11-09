<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - E-Jalin Jawa Timur</title>
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
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen flex flex-col justify-between selection:bg-[#ffb700] selection:text-[#0f2a71]">

    <!-- Simple Top Bar -->
    <header class="py-4 px-6 sm:px-8 border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-xl bg-[#0f2a71] border border-[#ffb700] flex items-center justify-center text-[#ffb700] font-black text-lg shadow-sm">
                    EJ
                </div>
                <div>
                    <span class="text-xl font-black tracking-tight text-[#0f2a71]">
                        E<span class="text-[#ffb700]">-JALIN</span>
                    </span>
                    <span class="text-[10px] font-bold uppercase text-slate-400 block leading-none">Dinas PU Bina Marga Jatim</span>
                </div>
            </a>
            <a href="{{ url('/') }}" class="text-xs sm:text-sm font-semibold text-slate-600 hover:text-[#0f2a71] flex items-center gap-1 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </header>

    <!-- Main Login Container -->
    <main class="flex-grow flex items-center justify-center p-4 sm:p-6 my-6">
        <div class="w-full max-w-md">
            
            <!-- Login Card -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/80 border-2 border-slate-200/90 overflow-hidden">
                <!-- Header Card Banner -->
                <div class="bg-[#0f2a71] px-8 pt-8 pb-7 text-center relative overflow-hidden border-b-4 border-[#ffb700]">
                    <!-- Background ambient glow -->
                    <div class="absolute -top-12 -right-12 w-36 h-36 bg-[#ffb700]/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-[#ffb700] shadow-md">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>

                    <h1 class="text-2xl font-black text-white tracking-tight">Portal Masuk E-Jalin</h1>
                    <p class="text-xs text-slate-200 mt-1">Sistem Integrasi Usulan &amp; Monitoring Infrastruktur</p>
                </div>

                <div class="p-8">
                    <!-- Session Status Alert -->
                    @if (session('status'))
                        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Error Alert -->
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

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Username Field -->
                        <div>
                            <label for="username" class="block text-xs font-bold uppercase tracking-wider text-[#0f2a71] mb-2">
                                Username
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
                                    placeholder="Masukkan username"
                                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 bg-slate-50/50 text-slate-900 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                                >
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#0f2a71] mb-2">
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
                                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 bg-slate-50/50 text-slate-900 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0f2a71] focus:border-transparent transition"
                                >
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-600">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#0f2a71] border-slate-300 focus:ring-[#0f2a71]">
                                <span>Ingat Saya di Perangkat Ini</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-4 rounded-xl bg-[#ffb700] text-[#0f2a71] font-black text-sm tracking-wide uppercase hover:bg-[#e69d00] shadow-md shadow-[#ffb700]/40 transition transform active:scale-[0.98] flex items-center justify-center gap-2"
                        >
                            <span>Masuk ke Sistem</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Credential Hint Box -->
                    <div class="mt-6 pt-5 border-t border-slate-200">
                        <div class="p-3.5 rounded-xl bg-[#0f2a71]/5 border border-[#0f2a71]/15 text-xs text-slate-600">
                            <div class="flex items-center gap-1.5 font-bold text-[#0f2a71] mb-1">
                                <svg class="w-4 h-4 text-[#ffb700]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Kredensial Default Login:</span>
                            </div>
                            <div class="font-mono text-[11px] text-slate-700 space-y-0.5">
                                <div>Username: <strong class="text-[#0f2a71] bg-white px-1.5 py-0.5 rounded border border-slate-200">admin</strong></div>
                                <div>Password: <strong class="text-[#0f2a71] bg-white px-1.5 py-0.5 rounded border border-slate-200">admin</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-center text-xs text-slate-500 mt-6">
                &copy; 2026 Dinas PU Bina Marga Provinsi Jawa Timur.
            </p>
        </div>
    </main>

</body>
</html>
