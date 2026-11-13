@extends('layouts.app')

@section('title', 'Peta Spasial GIS KML - E-Jalin Dinas PU Bina Marga Provinsi Jawa Timur')

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        .custom-popup .leaflet-popup-content-wrapper {
            padding: 0;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.15), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            border: 1px solid #cbd5e1;
        }
        .custom-popup .leaflet-popup-content {
            margin: 0;
            width: 330px !important;
            max-height: 420px;
            overflow-y: auto;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .custom-popup .leaflet-popup-tip {
            background: #0f2a71;
        }
        .custom-popup a.leaflet-popup-close-button {
            color: #ffffff !important;
            padding: 8px 8px 0 0 !important;
            font-size: 18px !important;
            z-index: 10;
        }
        .bridge-marker-pulse {
            position: relative;
        }
        .bridge-marker-pulse::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 9999px;
            border: 2px solid #f59e0b;
            animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 0.9; }
            50% { transform: scale(1.4); opacity: 0.3; }
            100% { transform: scale(1.8); opacity: 0; }
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumbs -->
    <nav class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 mb-4 gap-2">
        <span class="text-[#0f2a71] hover:underline cursor-pointer">GEOSPASIAL GIS</span>
        <span class="text-slate-400">&gt;</span>
        <span class="text-[#b87500] font-extrabold">PETA SPASIAL KML INTERAKTIF</span>
    </nav>

    <!-- Map & Sidepanel Container (Full Height Layout) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col" style="height: calc(100vh - 145px);">
        
        <!-- Top Toolbar / Action Bar -->
        <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 text-xs">
            
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#0f2a71] text-[#ffb700] flex items-center justify-center font-bold text-sm shadow-xs flex-shrink-0">
                    GIS
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 leading-tight">Visualisasi Geospasial Infrastruktur Jatim</h2>
                    <p class="text-[10px] text-slate-500 font-medium">Berdasarkan data KML Koridor (Merah), Ruas Jalan (Hijau), dan Jembatan (Titik Poin)</p>
                </div>
            </div>

            <!-- Layer Checkboxes & Actions -->
            <div class="flex items-center flex-wrap gap-2.5">
                
                <!-- Toggle Layer Koridor -->
                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 border border-red-200 text-red-700 font-bold text-[11px] cursor-pointer hover:bg-red-100 transition shadow-2xs select-none">
                    <input type="checkbox" id="toggleLayerKoridor" checked onchange="toggleLayer('koridor', this.checked)" class="w-3.5 h-3.5 accent-red-600 rounded">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span>
                    <span>Koridor (<span id="countKoridorKml">0</span>)</span>
                </label>

                <!-- Toggle Layer Ruas -->
                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-[11px] cursor-pointer hover:bg-emerald-100 transition shadow-2xs select-none">
                    <input type="checkbox" id="toggleLayerRuas" checked onchange="toggleLayer('ruas', this.checked)" class="w-3.5 h-3.5 accent-emerald-600 rounded">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    <span>Ruas Jalan (<span id="countRuasKml">0</span>)</span>
                </label>

                <!-- Toggle Layer Jembatan -->
                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 font-bold text-[11px] cursor-pointer hover:bg-amber-100 transition shadow-2xs select-none">
                    <input type="checkbox" id="toggleLayerJembatan" checked onchange="toggleLayer('jembatan', this.checked)" class="w-3.5 h-3.5 accent-amber-500 rounded">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span>Jembatan (<span id="countJembatanKml">0</span>)</span>
                </label>

                <!-- Basemap Dropdown -->
                <div class="relative">
                    <select id="selectBasemap" onchange="changeBasemap(this.value)" class="border border-slate-300 rounded-xl px-2.5 py-1.5 bg-white font-bold text-slate-700 text-[11px] focus:outline-none focus:ring-1 focus:ring-[#0f2a71] shadow-2xs">
                        <option value="osm">Peta OpenStreetMap</option>
                        <option value="google_hybrid">Google Satelit / Hybrid</option>
                        <option value="carto_positron">CartoDB Positron (Clean)</option>
                    </select>
                </div>

                <!-- Reset View Button -->
                <button type="button" onclick="resetMapView()" title="Reset Tampilan Jawa Timur" class="p-1.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-600 shadow-2xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>

                <!-- Toggle Sidebar Panel Button -->
                <button type="button" onclick="toggleSidePanel()" id="btnToggleSidePanel" title="Sembunyikan / Tampilkan Panel Daftar" class="px-2.5 py-1.5 rounded-xl bg-[#0f2a71] hover:bg-navy-700 text-white font-bold text-[11px] shadow-2xs transition flex items-center gap-1">
                    <span id="labelToggleSidePanel">Tutup Panel</span>
                    <span id="iconToggleSidePanel">◂</span>
                </button>

            </div>

        </div>

        <!-- Main Workspace: Left Sidepanel + Right Map -->
        <div class="flex-grow flex relative overflow-hidden">
            
            <!-- LEFT PANEL: Daftar & Filter Koridor, Ruas, Jembatan -->
            <div id="sidePanel" class="w-full lg:w-[380px] bg-white border-r border-slate-200 flex flex-col flex-shrink-0 transition-all duration-300 z-10">
                
                <!-- Filter Header in Sidepanel -->
                <div class="p-3 border-b border-slate-200 bg-slate-50/70 space-y-2.5">
                    
                    <!-- Search Input -->
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchKeyword" 
                            oninput="filterSidepanelItems()" 
                            placeholder="Cari nama koridor / ruas / jembatan..."
                            class="w-full border border-slate-300 rounded-xl pl-8 pr-3 py-1.5 text-xs bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0f2a71]"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Dropdown Filter Kab/Kota -->
                        <select id="filterKabupatenKota" onchange="filterSidepanelItems()" class="flex-grow border border-slate-300 rounded-xl px-2.5 py-1.5 text-[11px] bg-white font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0f2a71]">
                            <option value="">-- Semua Kabupaten / Kota --</option>
                            @foreach($kabupatenKotaList as $kab)
                                <option value="{{ $kab->id }}">{{ $kab->nama_kabupaten_kota }}</option>
                            @endforeach
                        </select>

                        <!-- Only Has KML checkbox -->
                        <label class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-600 cursor-pointer select-none whitespace-nowrap">
                            <input type="checkbox" id="filterOnlyKml" onchange="filterSidepanelItems()" class="w-3.5 h-3.5 accent-[#0f2a71] rounded">
                            <span>Ada KML</span>
                        </label>
                    </div>

                    <!-- Category Tab Pills -->
                    <div class="grid grid-cols-3 gap-1 p-1 bg-slate-200/80 rounded-xl text-xs font-bold">
                        <button type="button" onclick="switchTab('koridor')" id="tabBtnKoridor" class="py-1.5 px-2 rounded-lg bg-white text-[#0f2a71] shadow-xs text-center transition flex items-center justify-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-red-600"></span>
                            <span>Koridor</span>
                            <span id="badgeTabKoridor" class="text-[10px] px-1.5 py-0.2 rounded-full bg-red-100 text-red-700 font-extrabold">{{ count($koridorList) }}</span>
                        </button>
                        <button type="button" onclick="switchTab('ruas')" id="tabBtnRuas" class="py-1.5 px-2 rounded-lg text-slate-600 hover:text-slate-900 text-center transition flex items-center justify-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            <span>Ruas</span>
                            <span id="badgeTabRuas" class="text-[10px] px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800 font-extrabold">{{ count($ruasJalanList) }}</span>
                        </button>
                        <button type="button" onclick="switchTab('jembatan')" id="tabBtnJembatan" class="py-1.5 px-2 rounded-lg text-slate-600 hover:text-slate-900 text-center transition flex items-center justify-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>Jembatan</span>
                            <span id="badgeTabJembatan" class="text-[10px] px-1.5 py-0.2 rounded-full bg-amber-100 text-amber-800 font-extrabold">{{ count($jembatanList) }}</span>
                        </button>
                    </div>

                </div>

                <!-- Scrollable Item List Container -->
                <div class="flex-grow overflow-y-auto p-3 space-y-2.5 custom-scrollbar bg-slate-50/30" id="sidepanelItemList">
                    <!-- Cards will be rendered dynamically by JavaScript based on active tab and filters -->
                </div>

                <!-- Footer Summary of Sidepanel -->
                <div class="p-2.5 border-t border-slate-200 bg-white text-[10px] text-slate-500 flex items-center justify-between">
                    <span id="sidepanelStatusInfo">Memuat data layer geospasial...</span>
                    <span class="font-bold text-[#0f2a71]">Klik kartu untuk zoom ke peta</span>
                </div>

            </div>

            <!-- RIGHT PANEL: Interactive Leaflet Map Container -->
            <div class="flex-grow h-full relative" id="mapContainerWrapper">
                
                <div id="map" class="w-full h-full z-0"></div>

                <!-- Floating Legend & Info Box (Bottom Right) -->
                <div class="absolute bottom-5 right-5 z-[500] bg-white/95 backdrop-blur-xs p-3 rounded-2xl border border-slate-200 shadow-lg text-xs space-y-2 max-w-xs">
                    <div class="font-bold text-slate-900 flex items-center justify-between border-b border-slate-100 pb-1.5">
                        <span class="text-[11px] uppercase tracking-wider text-slate-500">LEGENDA LAYER PETA</span>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 font-bold">E-Jalin GIS</span>
                    </div>
                    <div class="space-y-1.5 text-[11px]">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-1.5 bg-red-600 rounded-full flex-shrink-0"></div>
                            <span class="font-semibold text-slate-700">Koridor Jalan (Garis Merah)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-1.5 bg-emerald-600 rounded-full flex-shrink-0"></div>
                            <span class="font-semibold text-slate-700">Ruas Jalan (Garis Hijau)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3.5 h-3.5 rounded-full bg-amber-500 border-2 border-white shadow-xs flex-shrink-0"></div>
                            <span class="font-semibold text-slate-700">Jembatan (Titik Poin Emas)</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- ============================================================== -->
    <!-- MODAL: DETAIL LENGKAP INFORMASI GEOSPASIAL                    -->
    <!-- ============================================================== -->
    <div id="modalDetail" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#ffb700] text-[#0f2a71] flex items-center justify-center font-black text-sm">
                        GIS
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight" id="modalDetailTitle">Detail Informasi Geospasial</h3>
                        <p class="text-[10px] text-slate-300 font-medium" id="modalDetailSubtitle">Informasi Teknis dan Spesifikasi Infrastruktur</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalDetail()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition cursor-pointer">
                    ✕
                </button>
            </div>

            <div class="p-6 space-y-4 text-xs text-slate-700 flex-grow" id="modalDetailBody">
                <!-- Content injected dynamically -->
            </div>

            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <div id="modalDetailDownloadContainer"></div>
                <button type="button" onclick="closeModalDetail()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Data Payloads from Backend
        const koridorData = @json($koridorList);
        const ruasData    = @json($ruasJalanList);
        const jembatanData= @json($jembatanList);

        // Map & Layer State
        let map = null;
        let basemapLayers = {};
        let layerGroups = {
            koridor: null,
            ruas: null,
            jembatan: null
        };
        let featureLayersMap = {}; // mapping key (type_id) to leaflet layer object
        let currentTab = 'koridor'; // 'koridor' | 'ruas' | 'jembatan'

        // Default East Java Bounding Center
        const JATIM_CENTER = [-7.7, 112.5];
        const JATIM_DEFAULT_ZOOM = 8;

        document.addEventListener('DOMContentLoaded', function () {
            initMap();
            loadAllKmlLayers();
            renderSidepanelItems();
            updateKmlCounters();

            // Auto fit seluruh garis koridor, ruas, dan titik jembatan saat pertama kali dibuka
            fitAllLoadedFeatures(false);
            setTimeout(() => {
                fitAllLoadedFeatures(true);
            }, 300);
        });

        // Initialize Map
        function initMap() {
            map = L.map('map', {
                center: JATIM_CENTER,
                zoom: JATIM_DEFAULT_ZOOM,
                zoomControl: true
            });

            // Basemaps definition
            basemapLayers['osm'] = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            });

            basemapLayers['google_hybrid'] = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                attribution: '&copy; Google Maps'
            });

            basemapLayers['carto_positron'] = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; CartoDB Positron'
            });

            // Set default basemap
            basemapLayers['osm'].addTo(map);

            // Custom map panes for explicit z-index hierarchy
            map.createPane('koridorPane');
            map.getPane('koridorPane').style.zIndex = 410;

            map.createPane('ruasPane');
            map.getPane('ruasPane').style.zIndex = 420;

            map.createPane('jembatanPane');
            map.getPane('jembatanPane').style.zIndex = 450;

            // Layer Groups
            layerGroups.koridor = L.layerGroup().addTo(map);
            layerGroups.ruas = L.layerGroup().addTo(map);
            layerGroups.jembatan = L.layerGroup().addTo(map);

            // Proteksi agar popup tidak pernah terpotong atau tertutup bar menu di atas peta saat terbuka
            map.on('popupopen', function (e) {
                const popupEl = e.popup.getElement();
                if (!popupEl) return;
                const mapEl = document.getElementById('map');
                const popupRect = popupEl.getBoundingClientRect();
                const mapRect = mapEl.getBoundingClientRect();

                // Batas minimal jarak dari tepi atas map container (ruang bebas bar menu)
                const minTopSafe = mapRect.top + 35;
                if (popupRect.top < minTopSafe) {
                    const diff = minTopSafe - popupRect.top;
                    map.panBy([0, -diff - 30], { animate: true, duration: 0.35 });
                }
            });
        }

        // Switch Basemap
        function changeBasemap(type) {
            Object.values(basemapLayers).forEach(layer => {
                if (map.hasLayer(layer)) map.removeLayer(layer);
            });
            if (basemapLayers[type]) {
                basemapLayers[type].addTo(map);
            }
        }

        // Reset View to East Java & Fit All Features
        function resetMapView() {
            fitAllLoadedFeatures(true);
        }

        // Fit Map Viewport to Show ALL Loaded Features (Garis Koridor, Ruas, & Poin Jembatan)
        function fitAllLoadedFeatures(animate = true) {
            if (!map) return;
            map.invalidateSize();

            const layers = [];
            if (layerGroups.koridor) layers.push(...layerGroups.koridor.getLayers());
            if (layerGroups.ruas) layers.push(...layerGroups.ruas.getLayers());
            if (layerGroups.jembatan) layers.push(...layerGroups.jembatan.getLayers());

            if (layers.length > 0) {
                const group = L.featureGroup(layers);
                const bounds = group.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, {
                        paddingTopLeft: [50, 80],
                        paddingBottomRight: [50, 50],
                        maxZoom: 15,
                        animate: animate
                    });
                    return;
                }
            }

            // Fallback: Default Center Jawa Timur
            map.setView(JATIM_CENTER, JATIM_DEFAULT_ZOOM);
        }

        // Toggle Layer Visibility
        function toggleLayer(type, isVisible) {
            if (!layerGroups[type]) return;
            if (isVisible) {
                if (!map.hasLayer(layerGroups[type])) map.addLayer(layerGroups[type]);
            } else {
                if (map.hasLayer(layerGroups[type])) map.removeLayer(layerGroups[type]);
            }
        }

        // Standardized Popup Options with Generous Top/Bottom Clearance
        const POPUP_OPTIONS = {
            className: 'custom-popup',
            autoPan: true,
            autoPanPaddingTopLeft: L.point(40, 110), // Ruang aman 110px dari batas atas peta / bar menu
            autoPanPaddingBottomRight: L.point(40, 40),
            offset: L.point(0, -10),
            maxHeight: 400
        };

        // Load All KML Layers Onto Map
        function loadAllKmlLayers() {
            // 1. Plot Koridor (MERAH TEBAL)
            koridorData.forEach(item => {
                if (!item.has_kml || !item.geo || !item.geo.segments) return;

                const polyline = L.polyline(item.geo.segments, {
                    pane: 'koridorPane',
                    color: '#dc2626', // Merah Kuat
                    weight: 6,
                    opacity: 0.9,
                    lineCap: 'round',
                    lineJoin: 'round'
                });

                polyline.on('mouseover', function () {
                    this.setStyle({ weight: 8, color: '#991b1b' });
                });
                polyline.on('mouseout', function () {
                    this.setStyle({ weight: 6, color: '#dc2626' });
                });

                polyline.bindPopup(buildPopupHtml(item), POPUP_OPTIONS);
                polyline.addTo(layerGroups.koridor);

                featureLayersMap[`koridor_${item.id}`] = {
                    layer: polyline,
                    item: item
                };
            });

            // 2. Plot Ruas Jalan (HIJAU TAJAM)
            ruasData.forEach(item => {
                if (!item.has_kml || !item.geo || !item.geo.segments) return;

                const polyline = L.polyline(item.geo.segments, {
                    pane: 'ruasPane',
                    color: '#059669', // Hijau Zamrud
                    weight: 4.5,
                    opacity: 0.95,
                    lineCap: 'round',
                    lineJoin: 'round'
                });

                polyline.on('mouseover', function () {
                    this.setStyle({ weight: 7, color: '#064e3b' });
                });
                polyline.on('mouseout', function () {
                    this.setStyle({ weight: 4.5, color: '#059669' });
                });

                polyline.bindPopup(buildPopupHtml(item), POPUP_OPTIONS);
                polyline.addTo(layerGroups.ruas);

                featureLayersMap[`ruas_${item.id}`] = {
                    layer: polyline,
                    item: item
                };
            });

            // 3. Plot Jembatan (TITIK POIN EMAS / AMBER RESPONSIVE)
            jembatanData.forEach(item => {
                if (!item.has_kml || !item.geo || !item.geo.point) return;

                const marker = L.circleMarker(item.geo.point, {
                    pane: 'jembatanPane',
                    radius: 7.5,
                    fillColor: '#f59e0b',
                    color: '#ffffff',
                    weight: 2.5,
                    opacity: 1,
                    fillOpacity: 0.95
                });

                marker.bindTooltip(`🌉 ${item.nama}`, {
                    permanent: false,
                    direction: 'top',
                    offset: [0, -8],
                    className: 'px-2 py-1 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-md border border-slate-700'
                });

                marker.on('mouseover', function () {
                    this.setRadius(10);
                    this.setStyle({ fillColor: '#fbbf24', color: '#0f2a71', weight: 3 });
                });
                marker.on('mouseout', function () {
                    const z = map.getZoom();
                    const r = z >= 13 ? 9 : (z >= 10 ? 7.5 : 5.5);
                    this.setRadius(r);
                    this.setStyle({ fillColor: '#f59e0b', color: '#ffffff', weight: 2.5 });
                });

                marker.bindPopup(buildPopupHtml(item), POPUP_OPTIONS);
                marker.addTo(layerGroups.jembatan);

                featureLayersMap[`jembatan_${item.id}`] = {
                    layer: marker,
                    item: item
                };
            });

            // Dynamic Responsive Sizing Saat Peta Di-zoom / Diperkecil
            map.on('zoomend', function () {
                const z = map.getZoom();

                // Ukuran dinamis garis Koridor
                const koridorWeight = z >= 13 ? 7 : (z >= 10 ? 5.5 : 4);
                layerGroups.koridor.eachLayer(l => {
                    if (l.setStyle) l.setStyle({ weight: koridorWeight });
                });

                // Ukuran dinamis garis Ruas Jalan
                const ruasWeight = z >= 13 ? 5.5 : (z >= 10 ? 4 : 2.5);
                layerGroups.ruas.eachLayer(l => {
                    if (l.setStyle) l.setStyle({ weight: ruasWeight });
                });

                // Ukuran dinamis radius titik poin Jembatan
                const bridgeRadius = z >= 13 ? 9 : (z >= 10 ? 7.5 : 5.5);
                layerGroups.jembatan.eachLayer(l => {
                    if (l.setRadius) l.setRadius(bridgeRadius);
                });
            });

            // Auto fit bounds initially if any features are loaded
            resetMapView();
        }

        // Update KML Counters in Toolbar
        function updateKmlCounters() {
            const countKoridor = koridorData.filter(i => i.has_kml).length;
            const countRuas    = ruasData.filter(i => i.has_kml).length;
            const countJembatan= jembatanData.filter(i => i.has_kml).length;

            document.getElementById('countKoridorKml').innerText = `${countKoridor}/${koridorData.length}`;
            document.getElementById('countRuasKml').innerText = `${countRuas}/${ruasData.length}`;
            document.getElementById('countJembatanKml').innerText = `${countJembatan}/${jembatanData.length}`;
        }

        // Switch Active Tab in Sidepanel
        function switchTab(tab) {
            currentTab = tab;
            
            const tabs = ['koridor', 'ruas', 'jembatan'];
            tabs.forEach(t => {
                const btn = document.getElementById(`tabBtn${capitalize(t)}`);
                if (t === tab) {
                    btn.className = 'py-1.5 px-2 rounded-lg bg-white text-[#0f2a71] shadow-xs text-center transition flex items-center justify-center gap-1 font-bold';
                } else {
                    btn.className = 'py-1.5 px-2 rounded-lg text-slate-600 hover:text-slate-900 text-center transition flex items-center justify-center gap-1 font-medium';
                }
            });

            renderSidepanelItems();
        }

        // Render Sidepanel Items
        function renderSidepanelItems() {
            const container = document.getElementById('sidepanelItemList');
            const search = document.getElementById('searchKeyword').value.toLowerCase().trim();
            const kabId = document.getElementById('filterKabupatenKota').value;
            const onlyKml = document.getElementById('filterOnlyKml').checked;

            let dataset = [];
            if (currentTab === 'koridor') dataset = koridorData;
            else if (currentTab === 'ruas') dataset = ruasData;
            else if (currentTab === 'jembatan') dataset = jembatanData;

            // Filter logic
            const filtered = dataset.filter(item => {
                // Search match
                const matchSearch = !search || 
                    item.nama.toLowerCase().includes(search) || 
                    (item.nomor && item.nomor.toLowerCase().includes(search)) ||
                    (item.kabupaten_kota_nama && item.kabupaten_kota_nama.toLowerCase().includes(search));

                // Kab/Kota match
                const matchKab = !kabId || String(item.kabupaten_kota_id) === String(kabId);

                // Only KML match
                const matchKml = !onlyKml || item.has_kml;

                return matchSearch && matchKab && matchKml;
            });

            // Update status text
            document.getElementById('sidepanelStatusInfo').innerText = `Menampilkan ${filtered.length} dari total ${dataset.length} data`;

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="py-12 px-4 text-center text-slate-400">
                        <div class="w-10 h-10 rounded-full bg-slate-200/60 flex items-center justify-center mx-auto mb-2 text-slate-500">
                            🔍
                        </div>
                        <p class="font-bold text-slate-600 text-xs">Tidak ditemukan data yang cocok</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Silakan sesuaikan kata kunci pencarian atau filter.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            filtered.forEach(item => {
                let typeBadge = '';
                let hoverBorderClass = 'hover:border-[#0f2a71]';
                let detailQuickBlock = '';

                if (item.layer_type === 'koridor') {
                    typeBadge = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-black tracking-wider uppercase bg-red-100 text-red-800 border border-red-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> KORIDOR (MERAH)
                    </span>`;
                    hoverBorderClass = 'hover:border-red-500';
                    detailQuickBlock = `
                        <div class="my-2 p-2 rounded-xl bg-red-50/80 border border-red-200/80 text-[11px] space-y-1">
                            <div class="flex items-start justify-between gap-1">
                                <span class="text-red-800 text-[10px] uppercase font-bold flex-shrink-0">Nama Koridor:</span>
                                <span class="font-extrabold text-slate-900 text-right text-xs leading-snug">${item.nama}</span>
                            </div>
                            <div class="flex items-center justify-between pt-1 border-t border-red-200/60 text-[10px]">
                                <span class="text-slate-500">No. Registrasi:</span>
                                <span class="font-mono font-bold text-slate-800">${item.nomor}</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="text-slate-500">Ruas Terhubung:</span>
                                <span class="font-bold text-red-700">${item.ruas_count} Ruas</span>
                            </div>
                        </div>
                    `;
                } else if (item.layer_type === 'ruas') {
                    typeBadge = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-black tracking-wider uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> RUAS JALAN (HIJAU)
                    </span>`;
                    hoverBorderClass = 'hover:border-emerald-500';
                    detailQuickBlock = `
                        <div class="my-2 p-2 rounded-xl bg-emerald-50/80 border border-emerald-200/80 text-[11px] space-y-1">
                            <div class="flex items-start justify-between gap-1">
                                <span class="text-emerald-800 text-[10px] uppercase font-bold flex-shrink-0">Nama Ruas:</span>
                                <span class="font-extrabold text-slate-900 text-right text-xs leading-snug">${item.nama}</span>
                            </div>
                            <div class="flex items-center justify-between pt-1 border-t border-emerald-200/60 text-[10px]">
                                <span class="text-slate-500">Koridor Induk:</span>
                                <span class="font-bold text-[#0f2a71] truncate max-w-[190px]">${item.koridor_nama}</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="text-slate-500">No. Registrasi:</span>
                                <span class="font-mono font-bold text-slate-800">${item.nomor}</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="text-slate-500">Jembatan:</span>
                                <span class="font-bold text-slate-700">${item.jembatan_count} Titik</span>
                            </div>
                        </div>
                    `;
                } else if (item.layer_type === 'jembatan') {
                    typeBadge = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-black tracking-wider uppercase bg-amber-100 text-amber-800 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> JEMBATAN (POIN EMAS)
                    </span>`;
                    hoverBorderClass = 'hover:border-amber-500';
                    detailQuickBlock = `
                        <div class="my-2 p-2 rounded-xl bg-amber-50/80 border border-amber-200/80 text-[11px] space-y-1">
                            <div class="flex items-start justify-between gap-1">
                                <span class="text-amber-900 text-[10px] uppercase font-bold flex-shrink-0">Nama Jembatan:</span>
                                <span class="font-extrabold text-slate-900 text-right text-xs leading-snug">${item.nama}</span>
                            </div>
                            <div class="flex items-center justify-between pt-1 border-t border-amber-200/60 text-[10px]">
                                <span class="text-slate-500">Ruas Jalan:</span>
                                <span class="font-bold text-[#0f2a71] truncate max-w-[190px]">${item.ruas_jalan_nama}</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="text-slate-500">Bentang Panjang:</span>
                                <span class="font-bold text-slate-800">${item.panjang}</span>
                            </div>
                        </div>
                    `;
                }

                const kmlStatusBadge = item.has_kml 
                    ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                         <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> KML Aktif
                       </span>`
                    : `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-200">
                         ✕ Belum Ada KML
                       </span>`;

                html += `
                    <div class="p-3 bg-white rounded-xl border border-slate-200 ${hoverBorderClass} shadow-2xs hover:shadow-sm transition cursor-pointer group" onclick="zoomToFeature('${item.layer_type}', ${item.id})">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <div>
                                ${typeBadge}
                            </div>
                            <div class="flex-shrink-0">
                                ${kmlStatusBadge}
                            </div>
                        </div>

                        <!-- Detail Sekilas Baca (Nama Ruas/Koridor/Jembatan & Info Utama) -->
                        ${detailQuickBlock}

                        <div class="grid grid-cols-2 gap-1.5 text-[11px] text-slate-600 mb-2 font-medium">
                            <div>
                                <span class="text-slate-400 text-[10px] block">Wilayah:</span>
                                <span class="truncate block font-semibold text-slate-800">${item.kabupaten_kota_nama}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 text-[10px] block">Panjang / Bentang:</span>
                                <span class="font-bold text-slate-900">${item.panjang}</span>
                            </div>
                        </div>

                        <!-- Action footer on card -->
                        <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between">
                            <button type="button" class="text-[11px] font-bold text-[#0f2a71] group-hover:text-[#ffb700] flex items-center gap-1 transition">
                                <span>🎯 Zoom ke Peta</span>
                            </button>
                            <button type="button" onclick="event.stopPropagation(); openDetailModal('${item.layer_type}', ${item.id})" class="text-[10px] font-bold text-slate-500 hover:text-slate-800 underline">
                                Detail Lengkap &rarr;
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Filter Trigger
        function filterSidepanelItems() {
            renderSidepanelItems();
        }

        // Zoom to Feature on Map dengan penyesuaian posisi agar popup tidak tertutup bar menu
        function zoomToFeature(type, id) {
            const key = `${type}_${id}`;
            const feature = featureLayersMap[key];

            if (!feature) {
                alert(`Objek ini (${type} #${id}) belum memiliki file KML geospasial pada sistem.`);
                return;
            }

            const item = feature.item;
            const layer = feature.layer;

            // Pastikan layer aktif dan terlihat
            const toggleId = `toggleLayer${capitalize(type)}`;
            const toggleInput = document.getElementById(toggleId);
            if (toggleInput && !toggleInput.checked) {
                toggleInput.checked = true;
                toggleLayer(type, true);
            }

            // Tutup popup aktif sebelumnya agar animasi transisi bersih
            map.closePopup();

            if (type === 'jembatan') {
                if (item.geo && item.geo.point) {
                    map.flyTo(item.geo.point, 16, {
                        duration: 0.8,
                        paddingTopLeft: [0, 160] // Memberikan ruang lega di atas marker agar popup tidak terpotong bar menu
                    });

                    let popupOpened = false;
                    const onMoveEnd = function () {
                        if (!popupOpened) {
                            popupOpened = true;
                            map.off('moveend', onMoveEnd);
                            layer.openPopup();
                        }
                    };
                    map.on('moveend', onMoveEnd);
                    // Fallback timeout
                    setTimeout(() => {
                        if (!popupOpened) {
                            popupOpened = true;
                            map.off('moveend', onMoveEnd);
                            layer.openPopup();
                        }
                    }, 900);
                }
            } else {
                if (item.geo && item.geo.bounds) {
                    map.flyToBounds(L.latLngBounds(item.geo.bounds), {
                        paddingTopLeft: [50, 160], // Memberikan ruang lega 160px di atas garis ruas/koridor agar popup tidak tertutup bar menu
                        paddingBottomRight: [50, 60],
                        maxZoom: 15,
                        duration: 0.8
                    });

                    let popupOpened = false;
                    const onMoveEnd = function () {
                        if (!popupOpened) {
                            popupOpened = true;
                            map.off('moveend', onMoveEnd);
                            layer.openPopup();
                        }
                    };
                    map.on('moveend', onMoveEnd);
                    // Fallback timeout
                    setTimeout(() => {
                        if (!popupOpened) {
                            popupOpened = true;
                            map.off('moveend', onMoveEnd);
                            layer.openPopup();
                        }
                    }, 900);
                }
            }
        }

        // Build Popup HTML Content dengan penegasan Nama Ruas / Koridor / Jembatan
        function buildPopupHtml(item) {
            const typeLabel = item.layer_type === 'koridor' ? 'KORIDOR JALAN' : (item.layer_type === 'ruas' ? 'RUAS JALAN' : 'JEMBATAN');
            const typeColor = item.layer_type === 'koridor' ? 'bg-red-600' : (item.layer_type === 'ruas' ? 'bg-emerald-600' : 'bg-amber-500');

            let highlightBox = '';
            let extraInfo = '';

            if (item.layer_type === 'ruas') {
                highlightBox = `
                    <div class="p-2.5 bg-emerald-50 rounded-xl border border-emerald-200/90 mb-2.5 shadow-2xs">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[9px] font-black uppercase tracking-wider text-emerald-800">Nama Ruas Jalan</span>
                            <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-200">${item.nomor}</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-900 leading-snug break-words">${item.nama}</h4>
                        <div class="mt-1.5 pt-1.5 border-t border-emerald-200/60 flex items-center justify-between text-[10px] text-slate-600 font-medium">
                            <span>Koridor Induk: <strong class="text-[#0f2a71]">${item.koridor_nama}</strong></span>
                            <span>Status: <strong class="text-slate-800">${item.status_jalan || 'Provinsi'}</strong></span>
                        </div>
                    </div>
                `;
                extraInfo = `
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Koridor Induk:</span>
                        <span class="font-bold text-[#0f2a71] text-right truncate max-w-[170px]">${item.koridor_nama}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Jembatan pada Ruas:</span>
                        <span class="font-bold text-slate-800">${item.jembatan_count} Titik</span>
                    </div>
                `;
            } else if (item.layer_type === 'koridor') {
                highlightBox = `
                    <div class="p-2.5 bg-red-50 rounded-xl border border-red-200/90 mb-2.5 shadow-2xs">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[9px] font-black uppercase tracking-wider text-red-800">Nama Koridor Jalan Arteri</span>
                            <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 rounded bg-red-100 text-red-800 border border-red-200">${item.nomor}</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-900 leading-snug break-words">${item.nama}</h4>
                        <div class="mt-1.5 pt-1.5 border-t border-red-200/60 flex items-center justify-between text-[10px] text-slate-600 font-medium">
                            <span>Ruas Terhubung: <strong class="text-red-700 font-bold">${item.ruas_count} Ruas</strong></span>
                            <span>Wilayah: <strong class="text-slate-800">${item.kabupaten_kota_nama}</strong></span>
                        </div>
                    </div>
                `;
                extraInfo = `
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Ruas Terhubung:</span>
                        <span class="font-bold text-slate-800">${item.ruas_count} Ruas</span>
                    </div>
                `;
            } else if (item.layer_type === 'jembatan') {
                highlightBox = `
                    <div class="p-2.5 bg-amber-50 rounded-xl border border-amber-200/90 mb-2.5 shadow-2xs">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[9px] font-black uppercase tracking-wider text-amber-900">Nama Jembatan</span>
                            <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-200">${item.nomor}</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-900 leading-snug break-words">${item.nama}</h4>
                        <div class="mt-1.5 pt-1.5 border-t border-amber-200/60 text-[10px] text-slate-600 font-medium">
                            <span>Berada di Ruas: <strong class="text-[#0f2a71]">${item.ruas_jalan_nama}</strong></span>
                        </div>
                    </div>
                `;
                extraInfo = `
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Ruas Jalan:</span>
                        <span class="font-bold text-[#0f2a71] text-right truncate max-w-[170px]">${item.ruas_jalan_nama}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Koridor Induk:</span>
                        <span class="font-bold text-slate-700 text-right truncate max-w-[170px]">${item.koridor_nama || '-'}</span>
                    </div>
                `;
            }

            return `
                <div class="overflow-hidden">
                    <!-- Popup Header -->
                    <div class="px-4 py-2 bg-[#0f2a71] text-white flex items-center justify-between border-b-2 border-[#ffb700]">
                        <div class="w-full pr-4 flex items-center justify-between">
                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black tracking-wider uppercase ${typeColor} text-white">
                                ${typeLabel}
                            </span>
                            <span class="text-[10px] font-bold text-slate-300 tracking-wide">Informasi Geospasial</span>
                        </div>
                    </div>

                    <!-- Popup Body -->
                    <div class="p-3.5 space-y-2 text-xs text-slate-700">
                        <!-- Kotak Penegasan Nama Detail Objek -->
                        ${highlightBox}

                        <div class="space-y-1 text-[11px]">
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Nomor Registrasi:</span>
                                <span class="font-mono font-bold text-slate-800">${item.nomor}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Wilayah Kab/Kota:</span>
                                <span class="font-bold text-slate-800">${item.kabupaten_kota_nama}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Panjang / Bentang:</span>
                                <span class="font-bold text-slate-900">${item.panjang}</span>
                            </div>
                            ${extraInfo}
                        </div>

                        <!-- Footer Action Buttons -->
                        <div class="pt-2 flex items-center justify-between gap-2 border-t border-slate-100">
                            ${item.download_url ? `
                                <a href="${item.download_url}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[10px] transition flex items-center gap-1 shadow-2xs">
                                    <span>📥 KML</span>
                                </a>
                            ` : ''}
                            <button type="button" onclick="openDetailModal('${item.layer_type}', ${item.id})" class="px-3 py-1 rounded-lg bg-[#0f2a71] hover:bg-navy-700 text-white font-bold text-[10px] transition ml-auto shadow-2xs">
                                Detail Lengkap &rarr;
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Open Detail Modal
        function openDetailModal(type, id) {
            let item = null;
            if (type === 'koridor') item = koridorData.find(i => i.id == id);
            else if (type === 'ruas') item = ruasData.find(i => i.id == id);
            else if (type === 'jembatan') item = jembatanData.find(i => i.id == id);

            if (!item) return;

            const typeTitle = item.layer_type === 'koridor' ? 'KORIDOR JALAN' : (item.layer_type === 'ruas' ? 'RUAS JALAN' : 'JEMBATAN');
            document.getElementById('modalDetailTitle').innerText = `Detail ${typeTitle}`;
            document.getElementById('modalDetailSubtitle').innerText = `Wilayah: ${item.kabupaten_kota_nama} | Nomor: ${item.nomor}`;

            let specificGrid = '';
            if (item.layer_type === 'ruas') {
                specificGrid = `
                    <div class="col-span-2 p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                        <span class="text-[10px] uppercase font-black text-emerald-800 block">Nama Lengkap Ruas Jalan</span>
                        <span class="font-black text-slate-900 text-sm leading-snug block mt-0.5">${item.nama}</span>
                        <span class="text-[11px] text-slate-600 block mt-1">Koridor Induk: <strong class="text-[#0f2a71]">${item.koridor_nama}</strong></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Status Jalan</span>
                        <span class="font-bold text-slate-800 text-xs">${item.status_jalan || 'Provinsi'}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Jembatan pada Ruas</span>
                        <span class="font-bold text-slate-800 text-xs">${item.jembatan_count} Titik Jembatan</span>
                    </div>
                    ${item.jembatan_list && item.jembatan_list.length > 0 ? `
                        <div class="col-span-2 text-[11px] bg-white p-2.5 rounded-lg border border-slate-200">
                            <span class="text-slate-400 text-[10px] block font-bold mb-1">Daftar Titik Jembatan:</span>
                            <span class="font-medium text-slate-700">${item.jembatan_list.join(', ')}</span>
                        </div>
                    ` : ''}
                `;
            } else if (item.layer_type === 'koridor') {
                specificGrid = `
                    <div class="col-span-2 p-3 bg-red-50 rounded-xl border border-red-200">
                        <span class="text-[10px] uppercase font-black text-red-800 block">Nama Lengkap Koridor Arteri</span>
                        <span class="font-black text-slate-900 text-sm leading-snug block mt-0.5">${item.nama}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Ruas Terhubung</span>
                        <span class="font-bold text-red-700 text-xs">${item.ruas_count} Ruas</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Wilayah Administratif</span>
                        <span class="font-semibold text-slate-800 text-xs">${item.kabupaten_kota_nama}</span>
                    </div>
                    ${item.ruas_list && item.ruas_list.length > 0 ? `
                        <div class="col-span-2 text-[11px] bg-white p-2.5 rounded-lg border border-slate-200 max-h-28 overflow-y-auto">
                            <span class="text-slate-400 text-[10px] block font-bold mb-1">Daftar Ruas Jalan Terkait:</span>
                            <ul class="list-disc pl-4 space-y-0.5 text-slate-700 font-medium">
                                ${item.ruas_list.map(r => `<li>${r}</li>`).join('')}
                            </ul>
                        </div>
                    ` : ''}
                `;
            } else if (item.layer_type === 'jembatan') {
                specificGrid = `
                    <div class="col-span-2 p-3 bg-amber-50 rounded-xl border border-amber-200">
                        <span class="text-[10px] uppercase font-black text-amber-900 block">Nama Lengkap Jembatan</span>
                        <span class="font-black text-slate-900 text-sm leading-snug block mt-0.5">${item.nama}</span>
                        <span class="text-[11px] text-slate-600 block mt-1">Berada di Ruas: <strong class="text-[#0f2a71]">${item.ruas_jalan_nama}</strong></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Koridor Induk</span>
                        <span class="font-bold text-slate-800 text-xs">${item.koridor_nama || '-'}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Bentang Panjang</span>
                        <span class="font-bold text-[#0f2a71] text-xs">${item.panjang}</span>
                    </div>
                `;
            }

            const body = document.getElementById('modalDetailBody');
            body.innerHTML = `
                <div class="space-y-4">
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 grid grid-cols-2 gap-3">
                        ${specificGrid}
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Nomor Registrasi</span>
                            <span class="font-mono font-bold text-slate-800 text-xs">${item.nomor}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Panjang / Dimensi</span>
                            <span class="font-bold text-[#0f2a71] text-xs">${item.panjang}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Kabupaten / Kota</span>
                            <span class="font-semibold text-slate-800 text-xs">${item.kabupaten_kota_nama}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">File KML</span>
                            <span class="font-mono text-xs text-slate-700 truncate block">${item.file_kml || 'Belum diunggah'}</span>
                        </div>
                    </div>
                </div>
            `;

            const dlContainer = document.getElementById('modalDetailDownloadContainer');
            if (item.download_url) {
                dlContainer.innerHTML = `
                    <a href="${item.download_url}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center gap-1.5 shadow-2xs">
                        <span>📥 Unduh File KML</span>
                    </a>
                `;
            } else {
                dlContainer.innerHTML = '';
            }

            document.getElementById('modalDetail').classList.remove('hidden');
        }

        function closeModalDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        // Toggle Sidepanel Visibility
        function toggleSidePanel() {
            const panel = document.getElementById('sidePanel');
            const label = document.getElementById('labelToggleSidePanel');
            const icon = document.getElementById('iconToggleSidePanel');

            if (panel.classList.contains('hidden')) {
                panel.classList.remove('hidden');
                label.innerText = 'Tutup Panel';
                icon.innerText = '◂';
            } else {
                panel.classList.add('hidden');
                label.innerText = 'Buka Panel';
                icon.innerText = '▸';
            }

            // Invalidate map size to adapt smoothly
            setTimeout(() => {
                map.invalidateSize();
            }, 350);
        }

        // Helper Capitalize
        function capitalize(s) {
            return s.charAt(0).toUpperCase() + s.slice(1);
        }

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModalDetail();
            }
        });
    </script>
@endpush
