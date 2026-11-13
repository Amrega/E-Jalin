<!-- Top Main Header (SiTIA / E-Jalin Style) -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">
            
            <!-- Left: Sidebar Toggle & Brand -->
            <div class="flex items-center gap-3">
                <button id="toggleSidebarBtn" class="p-2 rounded-lg text-slate-500 hover:text-[#0f2a71] hover:bg-slate-100 transition focus:outline-none" title="Toggle Sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- E-Jalin Brand Logo inspired by SiTIA layout -->
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

            <!-- Center/Left: Tahun Anggaran Pill -->
            <div class="hidden md:flex items-center gap-2">
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-lg border border-slate-300 bg-slate-50 text-xs font-semibold text-slate-700">
                    <span>Tahun Anggaran :</span>
                    <select class="bg-transparent font-bold text-[#0f2a71] focus:outline-none cursor-pointer">
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
                            {{ Auth::user()->name ?? 'Administrator E-Jalin' }}
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">Dinas PU Bina Marga Prov. Jatim</span>
                    </div>
                </div>

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
