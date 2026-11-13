<!-- Sidebar Navigation (Deep Navy Style) -->
<aside id="sidebar" class="w-64 bg-[#09183f] text-slate-300 flex-shrink-0 flex flex-col border-r border-[#0f2a71] transition-all duration-300 ease-in-out select-none">
    
    <!-- User Profile Brief in Sidebar -->
    <div class="p-4 border-b border-[#0f2a71]/80 bg-[#071333]/60 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#0f2a71] to-[#1a3d99] border border-[#ffb700]/40 flex items-center justify-center text-[#ffb700] font-black text-xs shadow-xs flex-shrink-0">
            {{ strtoupper(substr(Auth::user()->name ?? 'EJ', 0, 2)) }}
        </div>
        <div class="overflow-hidden">
            <span class="block text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</span>
            <span class="block text-[10px] text-emerald-400 font-medium truncate flex items-center gap-1.5 mt-0.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ Auth::user()->role->nama_role ?? 'Dinas PU Bina Marga' }}
            </span>
        </div>
    </div>

    <!-- Scrollable Menu Section: Langsung Tampil Semua (Always Visible) -->
    <div class="p-3.5 flex-grow overflow-y-auto space-y-6 custom-scrollbar">

        <!-- 1. Induk Menu: Geospasial GIS -->
        <div>
            <div class="flex items-center gap-2.5 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-white">
                <div class="w-6 h-6 rounded-lg bg-[#0f2a71] text-[#ffb700] flex items-center justify-center shadow-2xs flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                <span>Geospasial GIS</span>
            </div>
            
            <div class="space-y-1 text-xs font-medium pl-3 border-l-2 border-[#0f2a71] ml-5 mt-2">
                <a href="{{ route('geospasial.index') }}" class="block py-2 px-3 rounded-lg transition {{ (request()->routeIs('geospasial*') || request()->is('dashboard*')) ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Peta Spasial GIS
                </a>
            </div>
        </div>

        <!-- 2. Induk Menu: Usulan PEMDA -->
        <div>
            <div class="flex items-center gap-2.5 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-white">
                <div class="w-6 h-6 rounded-lg bg-[#0f2a71] text-[#ffb700] flex items-center justify-center shadow-2xs flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <span>Usulan PEMDA</span>
            </div>

            <div class="space-y-1 text-xs font-medium pl-3 border-l-2 border-[#0f2a71] ml-5 mt-2">
                <a href="{{ route('koridor.index') }}" class="block py-2 px-3 rounded-lg transition {{ request()->routeIs('koridor*') ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Referensi Koridor
                </a>
                <a href="{{ route('ruas.index') }}" class="block py-2 px-3 rounded-lg transition {{ request()->routeIs('ruas*') ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Referensi Ruas Jalan
                </a>
                <a href="{{ route('jembatan.index') }}" class="block py-2 px-3 rounded-lg transition {{ (request()->routeIs('jembatan*') || request()->is('referensi-jembatan*')) ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Referensi Jembatan
                </a>
                <a href="{{ route('usulan-inpres.index') }}" class="block py-2 px-3 rounded-lg transition {{ request()->routeIs('usulan-inpres*') ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Usulan Inpres
                </a>
            </div>
        </div>

        <!-- 3. Induk Menu: Referensi Master -->
        <div>
            <div class="flex items-center gap-2.5 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-white">
                <div class="w-6 h-6 rounded-lg bg-[#0f2a71] text-[#ffb700] flex items-center justify-center shadow-2xs flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3s3.5-1 3.5-3V7c0-2-1.5-3-3.5-3S4 5 4 7zm9 0v10c0 2 1.5 3 3.5 3s3.5-1 3.5-3V7c0-2-1.5-3-3.5-3s-3.5 1-3.5 3z"></path>
                    </svg>
                </div>
                <span>Master</span>
            </div>

            <div class="space-y-1 text-xs font-medium pl-3 border-l-2 border-[#0f2a71] ml-5 mt-2">
                <a href="{{ route('kabupaten-kota.index') }}" class="block py-2 px-3 rounded-lg transition {{ request()->routeIs('kabupaten-kota*') ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Kab/Kota
                </a>
                <a href="{{ route('upt-dinas.index') }}" class="block py-2 px-3 rounded-lg transition {{ request()->routeIs('upt-dinas*') ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    UPT PJJ
                </a>
                <a href="{{ route('jenis-penanganan.index') }}" class="block py-2 px-3 rounded-lg transition {{ request()->routeIs('jenis-penanganan*') ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Jenis Penanganan
                </a>
                <a href="{{ route('tematik-kawasan.index') }}" class="block py-2 px-3 rounded-lg transition {{ request()->routeIs('tematik-kawasan*') ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Tematik Kawasan
                </a>
            </div>
        </div>

        <!-- 4. Induk Menu: Manajemen Akses -->
        <div>
            <div class="flex items-center gap-2.5 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-white">
                <div class="w-6 h-6 rounded-lg bg-[#0f2a71] text-[#ffb700] flex items-center justify-center shadow-2xs flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-[#ffb700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span>Manajemen Akses</span>
            </div>

            <div class="space-y-1 text-xs font-medium pl-3 border-l-2 border-[#0f2a71] ml-5 mt-2">
                <a href="{{ route('user.index') }}" class="block py-2 px-3 rounded-lg transition {{ request()->routeIs('user*') ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Pengguna
                </a>
                <a href="{{ route('role.index') }}" class="block py-2 px-3 rounded-lg transition {{ request()->routeIs('role*') ? 'bg-[#0f2a71] text-[#ffb700] font-bold border-l-4 border-[#ffb700] shadow-sm' : 'text-slate-300 hover:text-[#ffb700] hover:bg-white/5' }}">
                    Role
                </a>
            </div>
        </div>

    </div>

    <!-- Sidebar Footer Status -->
    <div class="p-3.5 bg-[#071333] border-t border-[#0f2a71] text-[11px] text-slate-400 flex items-center justify-between">
        <span>E-Jalin v1.0</span>
        <span class="flex items-center gap-1.5 text-emerald-400 font-bold">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Online
        </span>
    </div>
</aside>
