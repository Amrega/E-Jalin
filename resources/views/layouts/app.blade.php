<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Jalin Dinas PU Bina Marga Provinsi Jawa Timur')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
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
                            700: '#153894',
                            800: '#112d7a',
                            900: '#0f2a71',
                            950: '#09183f',
                        },
                        gold: {
                            DEFAULT: '#ffb700',
                            400: '#ffc42e',
                            500: '#ffb700',
                            600: '#e69d00',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Global Custom Styles -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <!-- Page Specific Styles (e.g. Leaflet CSS) -->
    @stack('styles')
    @yield('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans flex flex-col min-h-screen selection:bg-[#ffb700] selection:text-[#0f2a71]">

    <!-- Header Partial -->
    @include('layouts.header')

    <!-- Main Body Container: Sidebar + Content -->
    <div class="flex flex-grow relative overflow-hidden">
        
        <!-- Sidebar Partial -->
        @include('layouts.sidebar')

        <!-- Dynamic Main Page Content -->
        <main class="flex-grow p-4 sm:p-6 lg:p-8 overflow-y-auto">
            @yield('content')
        </main>

    </div>

    <!-- Modals Stack (Optional) -->
    @stack('modals')

    <!-- Core Global Script -->
    <script>
        // Toggle Sidebar Responsive
        const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
        const sidebar = document.getElementById('sidebar');
        if (toggleSidebarBtn && sidebar) {
            toggleSidebarBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-ml-64');
            });
        }
    </script>

    <!-- Page Specific Scripts -->
    @stack('scripts')
    @yield('scripts')
</body>
</html>
