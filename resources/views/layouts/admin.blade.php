<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Dashboard - BPJS Care Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-bpjs-light flex h-screen overflow-hidden font-sans">
    
    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-bpjs-dark text-gray-300 flex flex-col shadow-2xl z-50 border-r border-slate-700/50 transform -translate-x-full md:translate-x-0 md:relative transition-transform duration-300">
        <div class="h-20 flex items-center justify-between border-b border-slate-700/50 px-6">
            <span class="text-2xl font-black tracking-tighter text-white">BPJS<span class="text-bpjs-teal">Ops</span> Center</span>
            <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="px-6 py-6 border-b border-slate-700/50 bg-black/10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-bpjs-teal flex items-center justify-center text-bpjs-dark text-xl font-bold shadow-lg border-2 border-white/20 shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="font-bold text-white text-lg truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-bpjs-teal font-semibold tracking-wider uppercase truncate">Senior Administrator</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-bpjs-blue to-bpjs-teal text-white shadow-lg font-semibold' : 'hover:bg-slate-700/50 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-bpjs-teal group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard Analytics
            </a>
            
            <a href="{{ route('admin.pengaduan.index') }}" class="group flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.pengaduan.*') ? 'bg-gradient-to-r from-bpjs-blue to-bpjs-teal text-white shadow-lg font-semibold' : 'hover:bg-slate-700/50 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.pengaduan.*') ? 'text-white' : 'text-bpjs-teal group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Manajemen Pengaduan
            </a>
            
            <a href="{{ route('admin.kategori.index') }}" class="group flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.kategori.*') ? 'bg-gradient-to-r from-bpjs-blue to-bpjs-teal text-white shadow-lg font-semibold' : 'hover:bg-slate-700/50 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.kategori.*') ? 'text-white' : 'text-bpjs-teal group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kelola Kategori
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-bpjs-blue to-bpjs-teal text-white shadow-lg font-semibold' : 'hover:bg-slate-700/50 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-bpjs-teal group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Manajemen Pengguna
            </a>
            
            <a href="{{ route('admin.faq.index') }}" class="group flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.faq.*') ? 'bg-gradient-to-r from-bpjs-blue to-bpjs-teal text-white shadow-lg font-semibold' : 'hover:bg-slate-700/50 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.faq.*') ? 'text-white' : 'text-bpjs-teal group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pusat Bantuan (FAQ)
            </a>
        </nav>
        
        <div class="p-4 border-t border-slate-700/50 bg-black/10">
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-500 hover:text-white text-red-400 font-bold py-3 px-4 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="md:block">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-full relative overflow-hidden w-full">
        <header class="h-20 glass-morph shadow-sm flex items-center justify-between px-4 md:px-10 z-30 border-b border-gray-100 bg-white/90 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-bpjs-blue focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-bpjs-dark truncate max-w-[150px] md:max-w-full">@yield('page_title', 'Dashboard')</h1>
                    <p class="hidden md:block text-xs text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 md:gap-6">
                <div class="relative hidden md:block">
                    <input type="text" placeholder="Cari Kode Tiket..." class="bg-gray-100 border border-gray-200 rounded-full px-5 py-2 w-48 lg:w-64 text-sm focus:ring-2 focus:ring-bpjs-blue outline-none transition">
                    <svg class="w-5 h-5 text-gray-400 absolute right-4 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <button class="relative text-gray-400 hover:text-bpjs-blue transition">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 md:w-3 md:h-3 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-10 bg-bpjs-light w-full">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>