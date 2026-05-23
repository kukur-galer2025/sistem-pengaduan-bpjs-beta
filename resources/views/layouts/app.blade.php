<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPJS Care Portal - Layanan Pengaduan Resmi</title>
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 flex flex-col min-h-screen font-sans text-slate-800 antialiased">
    
    <header data-aos="fade-down" data-aos-duration="800" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <div class="flex items-center gap-10">
                    <a href="/" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-bpjs-blue to-bpjs-teal shadow-inner flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
                            <i class="ph-fill ph-shield-check text-white text-2xl"></i>
                        </div>
                        <span class="text-2xl font-black text-bpjs-dark tracking-tighter">BPJS<span class="text-bpjs-blue">Care</span></span>
                    </a>

                    <nav class="hidden lg:flex items-center gap-8">
                        <a href="/" class="flex items-center gap-1.5 text-sm font-bold text-gray-500 hover:text-bpjs-blue transition">
                            <i class="ph-fill ph-house text-lg"></i> Beranda
                        </a>
                        @auth
                            <a href="{{ route('pengaduan.index') }}" class="flex items-center gap-1.5 text-sm font-bold text-gray-500 hover:text-bpjs-blue transition">
                                <i class="ph-fill ph-clock-counter-clockwise text-lg"></i> Riwayat Laporan
                            </a>
                        @endauth
                        <a href="#" class="flex items-center gap-1.5 text-sm font-bold text-gray-500 hover:text-bpjs-blue transition">
                            <i class="ph-fill ph-question text-lg"></i> F.A.Q
                        </a>
                    </nav>
                </div>

                <div class="flex items-center gap-6">
                    
                    <div class="hidden xl:flex items-center gap-2.5 px-4 py-2 bg-slate-50 border border-slate-200 rounded-full shadow-sm">
                        <i class="ph-fill ph-clock text-bpjs-teal text-lg"></i>
                        <span id="live-clock" class="text-[11px] font-extrabold text-slate-500 tracking-wider uppercase">Memuat waktu...</span>
                    </div>

                    <div class="hidden md:block h-8 w-px bg-gray-200"></div>

                    <div class="flex items-center gap-3">
                        @auth
                            <div class="relative" id="profile-dropdown-container">
                                <button onclick="toggleDropdown()" class="flex items-center gap-2 md:gap-3 bg-white pl-1.5 pr-3 md:pl-2 md:pr-4 py-1.5 rounded-full border border-gray-200 shadow-sm hover:shadow-md transition focus:outline-none focus:ring-2 focus:ring-bpjs-blue/50">
                                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gradient-to-r from-bpjs-dark to-slate-800 flex items-center justify-center text-white text-[10px] md:text-xs font-bold shadow-inner shrink-0">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    </div>
                                    <span class="font-bold text-bpjs-dark text-xs md:text-sm truncate max-w-[80px] md:max-w-[150px]">{{ Auth::user()->name }}</span>
                                    <i id="dropdown-icon" class="ph-bold ph-caret-down text-gray-400 text-xs md:text-sm transition-transform duration-300"></i>
                                </button>

                                <div id="dropdown-panel" class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-gray-100 py-2 hidden z-50 transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                                    
                                    <div class="px-5 py-3 border-b border-gray-50 mb-1">
                                        <p class="text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-0.5">Login Sebagai</p>
                                        <p class="text-sm font-bold text-bpjs-dark truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <a href="#" class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-slate-50 hover:text-bpjs-blue transition">
                                        <i class="ph-fill ph-user-gear text-lg text-gray-400"></i> Edit Profil
                                    </a>
                                    
                                    <div class="h-px bg-gray-100 my-1 mx-4"></div>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 transition text-left">
                                            <i class="ph-bold ph-sign-out text-lg"></i> Keluar Sistem
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-xs md:text-sm font-bold text-gray-600 hover:text-bpjs-blue transition px-1 md:px-2">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-bpjs-blue to-bpjs-teal px-4 py-2 md:px-6 md:py-2.5 text-xs md:text-sm font-bold text-white shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all rounded-full flex items-center gap-1.5 md:gap-2">
                                Daftar <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        @endauth
                        
                        <!-- Hamburger Button -->
                        <button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="lg:hidden ml-2 text-gray-500 hover:text-bpjs-blue focus:outline-none">
                            <i id="hamburger-icon" class="ph-bold ph-list text-2xl transition-transform duration-300"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Menu -->
    <div id="mobile-menu" class="lg:hidden fixed inset-0 z-40 bg-white/95 backdrop-blur-md transform -translate-y-full transition-transform duration-300 pt-24 border-b border-gray-100 shadow-xl hidden">
        <nav class="flex flex-col px-6 py-8 gap-6">
            <a href="/" class="flex items-center gap-4 text-lg font-bold text-gray-600 hover:text-bpjs-blue transition">
                <i class="ph-fill ph-house text-3xl text-bpjs-teal"></i> Beranda
            </a>
            @auth
                <a href="{{ route('pengaduan.index') }}" class="flex items-center gap-4 text-lg font-bold text-gray-600 hover:text-bpjs-blue transition">
                    <i class="ph-fill ph-clock-counter-clockwise text-3xl text-bpjs-teal"></i> Riwayat Laporan
                </a>
            @endauth
            <a href="#" class="flex items-center gap-4 text-lg font-bold text-gray-600 hover:text-bpjs-blue transition">
                <i class="ph-fill ph-question text-3xl text-bpjs-teal"></i> F.A.Q
            </a>
        </nav>
    </div>

    <main data-aos="fade-up" data-aos-duration="1000" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-16 flex-grow w-full relative z-10">
        @yield('content')
    </main>

    <footer class="bg-bpjs-dark text-slate-300 mt-auto border-t-[6px] border-bpjs-teal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 lg:gap-8">
                
                <div data-aos="fade-right" class="md:col-span-2 pr-0 lg:pr-12">
                    <a href="/" class="flex items-center gap-2 mb-6">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-bpjs-blue to-bpjs-teal flex items-center justify-center">
                            <i class="ph-fill ph-shield-check text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-black text-white tracking-tighter">BPJS Care Portal</span>
                    </a>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-md">
                        Portal pengaduan resmi dan terintegrasi. Komitmen kami untuk menghadirkan transparansi dan perbaikan berkelanjutan dalam pelayanan kesehatan masyarakat Indonesia.
                    </p>
                </div>
                
                <div data-aos="fade-up" data-aos-delay="100">
                    <h5 class="font-extrabold text-white mb-6 text-sm tracking-widest uppercase">Tautan Cepat</h5>
                    <ul class="space-y-3.5 text-sm font-medium text-slate-400">
                        <li><a href="#" class="hover:text-white flex items-center gap-2 transition"><i class="ph-fill ph-book-open text-bpjs-teal"></i> Panduan Pelaporan</a></li>
                        <li><a href="#" class="hover:text-white flex items-center gap-2 transition"><i class="ph-fill ph-activity text-bpjs-teal"></i> Status Layanan</a></li>
                        <li><a href="#" class="hover:text-white flex items-center gap-2 transition"><i class="ph-fill ph-headset text-bpjs-teal"></i> Hubungi Kami</a></li>
                    </ul>
                </div>
                
                <div data-aos="fade-left" data-aos-delay="200">
                    <h5 class="font-extrabold text-white mb-6 text-sm tracking-widest uppercase">Kontak Resmi</h5>
                    <ul class="space-y-4 text-sm font-medium text-slate-400">
                        <li class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-bpjs-teal flex-shrink-0">
                                <i class="ph-fill ph-phone-call text-lg"></i>
                            </div>
                            <div>
                                <span class="block text-xs mb-0.5">Call Center Nasional</span>
                                <span class="text-amber-400 font-black text-xl tracking-widest">165</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-bpjs-teal flex-shrink-0">
                                <i class="ph-fill ph-envelope-simple text-lg"></i>
                            </div>
                            <span>info@bpjs-kesehatan.go.id</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-black/40 border-t border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-500 font-semibold">
                    &copy; {{ date('Y') }} BPJS Kesehatan. Hak Cipta Dilindungi Undang-Undang.
                </p>
                <p class="text-xs text-slate-600 font-bold tracking-wider">
                    EXECUTIVE CARE SYSTEM
                </p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 30 });
    </script>

    <script>
        function updateClock() {
            const now = new Date();
            const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = now.toLocaleDateString('id-ID', optionsDate);
            
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const clockElement = document.getElementById('live-clock');
            if (clockElement) {
                clockElement.innerHTML = `${formattedDate} <span class="mx-1.5 text-slate-300">|</span> <span class="text-bpjs-blue font-black">${hours}:${minutes}:${seconds} WIB</span>`;
            }
        }
        setInterval(updateClock, 1000);
        updateClock(); 
    </script>

    <script>
        const dropdownPanel = document.getElementById('dropdown-panel');
        const dropdownIcon = document.getElementById('dropdown-icon');
        const dropdownContainer = document.getElementById('profile-dropdown-container');
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');

        function toggleDropdown() {
            if (dropdownPanel.classList.contains('hidden')) {
                dropdownPanel.classList.remove('hidden');
                setTimeout(() => {
                    dropdownPanel.classList.remove('opacity-0', 'scale-95');
                    dropdownPanel.classList.add('opacity-100', 'scale-100');
                    dropdownIcon.classList.add('rotate-180');
                }, 10);
            } else {
                closeDropdown();
            }
        }

        function closeDropdown() {
            if(dropdownPanel && !dropdownPanel.classList.contains('hidden')) {
                dropdownPanel.classList.remove('opacity-100', 'scale-100');
                dropdownPanel.classList.add('opacity-0', 'scale-95');
                dropdownIcon.classList.remove('rotate-180');
                
                setTimeout(() => {
                    dropdownPanel.classList.add('hidden');
                }, 200); 
            }
        }

        function toggleMobileMenu() {
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                // Allow browser to render display:block before translating
                setTimeout(() => {
                    mobileMenu.classList.remove('-translate-y-full');
                    hamburgerIcon.classList.replace('ph-list', 'ph-x');
                }, 10);
            } else {
                mobileMenu.classList.add('-translate-y-full');
                hamburgerIcon.classList.replace('ph-x', 'ph-list');
                
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            }
        }

        // Tutup dropdown jika user klik area di luar kotak dropdown
        window.addEventListener('click', function(e) {
            if (dropdownContainer && !dropdownContainer.contains(e.target)) {
                closeDropdown();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#118AB2', 
                    customClass: { popup: 'rounded-2xl shadow-xl border border-gray-100' }
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: '{{ $errors->first() }}',
                    confirmButtonColor: '#EF4444',
                    customClass: { popup: 'rounded-2xl shadow-xl border border-gray-100' }
                });
            @endif
        });
    </script>
</body>
</html>