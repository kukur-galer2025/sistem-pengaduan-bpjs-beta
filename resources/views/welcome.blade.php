@extends('layouts.app')

@section('content')

@if (session('status'))
    <div data-aos="fade-down" class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm border border-green-200 shadow-sm flex items-start gap-3">
        <i class="ph-fill ph-check-circle text-xl mt-0.5"></i>
        <p class="font-medium">{{ session('status') }}</p>
    </div>
@endif

<div data-aos="zoom-in" data-aos-duration="1000" class="relative bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden mb-24 mt-6">
    <div class="absolute inset-0 bg-gradient-to-br from-bpjs-blue/5 via-bpjs-teal/5 to-white z-0"></div>
    
    <div class="grid grid-cols-1 md:grid-cols-5 relative z-10">
        <div class="md:col-span-3 p-8 md:p-16 lg:p-20 flex flex-col justify-center">
            <div data-aos="fade-right" data-aos-delay="100" class="inline-flex items-center gap-2 bg-bpjs-teal/10 text-bpjs-teal px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-6 border border-bpjs-teal/20 shadow-inner w-max">
                <i class="ph-bold ph-megaphone text-sm"></i>
                Layanan Pengaduan Resmi
            </div>
            
            <h1 data-aos="fade-right" data-aos-delay="200" class="text-4xl md:text-5xl lg:text-6xl font-black text-bpjs-dark mb-6 leading-tight tracking-tighter">
                Sampaikan, Kami <span class="text-gradient bg-gradient-to-r from-bpjs-blue to-bpjs-teal bg-clip-text text-transparent">Dengarkan</span> & Perbaiki.
            </h1>
            
            <p data-aos="fade-right" data-aos-delay="300" class="text-xl text-gray-600 mb-10 leading-relaxed max-w-2xl">
                Suara Anda adalah kunci peningkatan kualitas layanan kami. Laporkan keluhan terkait fasilitas kesehatan atau administrasi BPJS melalui portal resmi yang transparan dan akuntabel.
            </p>
            
            <div data-aos="fade-up" data-aos-delay="400" class="flex flex-col sm:flex-row gap-4 w-full">
                <a href="{{ route('pengaduan.create') }}" class="btn-primary-grad text-base md:text-lg px-6 md:px-10 py-3 md:py-4 shadow-xl flex items-center justify-center gap-2 hover:-translate-y-1 transition-transform w-full sm:w-auto">
                    Buat Pengaduan <i class="ph-bold ph-arrow-right"></i>
                </a>
                
                @guest
                    <a href="{{ route('register') }}" class="bg-white text-bpjs-dark border-2 border-gray-200 hover:border-lux-gold hover:text-lux-gold text-base md:text-lg px-6 md:px-10 py-3 md:py-4 rounded-xl font-bold transition flex items-center justify-center gap-2 w-full sm:w-auto">
                        Daftar Akun <i class="ph-bold ph-user-plus"></i>
                    </a>
                @else
                    <a href="{{ route('pengaduan.index') }}" class="bg-white text-bpjs-dark border-2 border-gray-200 hover:border-bpjs-teal hover:text-bpjs-teal text-base md:text-lg px-6 md:px-10 py-3 md:py-4 rounded-xl font-bold transition flex items-center justify-center gap-2 w-full sm:w-auto">
                        Riwayat Saya <i class="ph-bold ph-clock-counter-clockwise"></i>
                    </a>
                @endguest
            </div>
        </div>
        
        <div class="md:col-span-2 bg-bpjs-dark/5 p-12 flex items-center justify-center border-l border-gray-100">
            <div data-aos="zoom-in" data-aos-delay="500" class="text-center text-bpjs-teal opacity-20 hover:opacity-100 transition duration-500 transform hover:scale-105">
                <i class="ph-fill ph-headset" style="font-size: 14rem;"></i>
            </div>
        </div>
    </div>
</div>

<div class="mb-24">
    <h2 data-aos="fade-up" class="text-sm font-bold tracking-widest text-center text-gray-500 uppercase mb-3">Keunggulan Portal</h2>
    <p data-aos="fade-up" data-aos-delay="100" class="text-3xl font-extrabold text-center text-bpjs-dark mb-12">Proses Transparan, Berkelas & Tepercaya.</p>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-6">
        <div data-aos="fade-up" data-aos-delay="100" class="card-luxury hover:-translate-y-2 group p-8 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-bpjs-blue/10 flex items-center justify-center mb-6 text-bpjs-blue border border-bpjs-blue/20 shadow-inner group-hover:bg-bpjs-blue group-hover:text-white transition">
                <i class="ph-fill ph-lightning text-3xl"></i>
            </div>
            <h4 class="text-xl font-bold text-bpjs-dark mb-3">Respon Cepat</h4>
            <p class="text-gray-600 text-sm leading-relaxed">Sistem kami memastikan setiap laporan masuk segera diverifikasi oleh petugas dalam waktu kurang dari 24 jam.</p>
        </div>
        
        <div data-aos="fade-up" data-aos-delay="200" class="card-luxury hover:-translate-y-2 group p-8 rounded-2xl bg-white border border-bpjs-teal/20 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-bpjs-teal"></div>
            <div class="w-14 h-14 rounded-2xl bg-bpjs-teal/10 flex items-center justify-center mb-6 text-bpjs-teal border border-bpjs-teal/20 shadow-inner group-hover:bg-bpjs-teal group-hover:text-white transition">
                <i class="ph-fill ph-crosshair text-3xl"></i>
            </div>
            <h4 class="text-xl font-bold text-bpjs-dark mb-3">Pelacakan Real-time</h4>
            <p class="text-gray-600 text-sm leading-relaxed">Pantau status penanganan pengaduan Anda setiap saat melalui <em class="font-semibold text-bpjs-teal">Vertical Timeline</em> yang interaktif.</p>
        </div>
        
        <div data-aos="fade-up" data-aos-delay="300" class="card-luxury hover:-translate-y-2 group p-8 rounded-2xl bg-lux-ivory border border-lux-gold/20 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-lux-gold/10 flex items-center justify-center mb-6 text-lux-gold border border-lux-gold/20 shadow-inner group-hover:bg-lux-gold group-hover:text-bpjs-dark transition">
                <i class="ph-fill ph-shield-check text-3xl"></i>
            </div>
            <h4 class="text-xl font-bold text-bpjs-dark mb-3">Keamanan Data</h4>
            <p class="text-gray-600 text-sm leading-relaxed">Data pribadi dan lampiran bukti Anda dilindungi dengan standar enkripsi tinggi tingkat korporat.</p>
        </div>
    </div>
</div>

<div class="mb-24 bg-bpjs-dark rounded-3xl p-10 lg:p-16 relative overflow-hidden text-white shadow-2xl">
    <div class="absolute top-0 right-0 w-96 h-96 bg-bpjs-blue opacity-10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-bpjs-teal opacity-10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

    <div class="relative z-10">
        <div class="text-center mb-16">
            <h2 data-aos="fade-up" class="text-sm font-bold tracking-widest text-bpjs-teal uppercase mb-3">Sistem Terintegrasi</h2>
            <p data-aos="fade-up" data-aos-delay="100" class="text-3xl font-extrabold text-white">Bagaimana Pengaduan Anda Diproses?</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div data-aos="fade-right" data-aos-delay="100" class="relative group">
                <div class="w-16 h-16 rounded-full bg-white/10 border-2 border-white/20 flex items-center justify-center text-2xl font-bold mb-6 group-hover:bg-bpjs-teal transition duration-300">1</div>
                <h4 class="text-xl font-bold mb-3">Registrasi & Lapor</h4>
                <p class="text-gray-400 text-sm leading-relaxed">Buat akun resmi menggunakan NIK KTP Anda, lalu tuliskan kronologi keluhan dengan jelas beserta bukti lampiran.</p>
                <div class="hidden md:block absolute top-8 left-20 w-full h-0.5 bg-gradient-to-r from-white/20 to-transparent"></div>
            </div>

            <div data-aos="fade-right" data-aos-delay="200" class="relative group">
                <div class="w-16 h-16 rounded-full bg-white/10 border-2 border-white/20 flex items-center justify-center text-2xl font-bold mb-6 group-hover:bg-bpjs-blue transition duration-300">2</div>
                <h4 class="text-xl font-bold mb-3">Verifikasi Petugas</h4>
                <p class="text-gray-400 text-sm leading-relaxed">Tiket pengaduan Anda akan masuk ke *dashboard* petugas kami untuk diperiksa keabsahan dan kategori masalahnya.</p>
                <div class="hidden md:block absolute top-8 left-20 w-full h-0.5 bg-gradient-to-r from-white/20 to-transparent"></div>
            </div>

            <div data-aos="fade-right" data-aos-delay="300" class="relative group">
                <div class="w-16 h-16 rounded-full bg-white/10 border-2 border-white/20 flex items-center justify-center text-2xl font-bold mb-6 group-hover:bg-lux-gold group-hover:text-bpjs-dark transition duration-300">3</div>
                <h4 class="text-xl font-bold mb-3">Eskalasi & Proses</h4>
                <p class="text-gray-400 text-sm leading-relaxed">Keluhan diteruskan ke departemen terkait (Faskes/Administratif) untuk dicarikan solusi yang konkret dan mengikat.</p>
                <div class="hidden md:block absolute top-8 left-20 w-full h-0.5 bg-gradient-to-r from-white/20 to-transparent"></div>
            </div>

            <div data-aos="fade-right" data-aos-delay="400" class="relative group">
                <div class="w-16 h-16 rounded-full bg-white/10 border-2 border-white/20 flex items-center justify-center text-2xl font-bold mb-6 group-hover:bg-green-500 transition duration-300"><i class="ph-bold ph-check"></i></div>
                <h4 class="text-xl font-bold mb-3">Selesai & Evaluasi</h4>
                <p class="text-gray-400 text-sm leading-relaxed">Anda akan menerima pemberitahuan penyelesaian. Laporan Anda digunakan sebagai bahan audit perbaikan BPJS.</p>
            </div>
        </div>
    </div>
</div>

<div class="mb-24">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10 flex flex-col md:flex-row items-center justify-between gap-10">
        <div data-aos="fade-right" class="md:w-1/2">
            <h3 class="text-3xl font-extrabold text-bpjs-dark mb-4">Membangun Kepercayaan Melalui Angka</h3>
            <p class="text-gray-600 leading-relaxed">Kami tidak hanya menampung keluhan, tetapi memastikan setiap tiket diselesaikan secara tuntas. Transparansi adalah bentuk pertanggungjawaban publik kami.</p>
        </div>
        
        <div class="md:w-1/2 grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 w-full mt-8 md:mt-0">
            <div data-aos="zoom-in" data-aos-delay="100" class="bg-blue-50 rounded-2xl p-6 border border-blue-100 text-center hover:shadow-md transition">
                <i class="ph-fill ph-users-three text-3xl text-bpjs-blue mb-2"></i>
                <h5 class="text-3xl lg:text-4xl font-black text-bpjs-dark mb-1">12K+</h5>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pelapor Aktif</p>
            </div>
            <div data-aos="zoom-in" data-aos-delay="200" class="bg-teal-50 rounded-2xl p-6 border border-teal-100 text-center hover:shadow-md transition">
                <i class="ph-fill ph-check-circle text-3xl text-bpjs-teal mb-2"></i>
                <h5 class="text-3xl lg:text-4xl font-black text-bpjs-dark mb-1">98%</h5>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Tingkat Penyelesaian</p>
            </div>
        </div>
    </div>
</div>

<div data-aos="zoom-in-up" class="bg-gradient-to-r from-bpjs-blue to-bpjs-teal rounded-3xl p-12 text-center text-white shadow-2xl relative overflow-hidden">
    <svg class="absolute inset-0 w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
            </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#grid)" />
    </svg>

    <div class="relative z-10 max-w-2xl mx-auto">
        <i class="ph-fill ph-hand-heart text-6xl mb-6 opacity-90"></i>
        <h2 class="text-3xl md:text-4xl font-black mb-6 leading-tight">Siap Membantu Meningkatkan Layanan Kesehatan Nasional?</h2>
        <p class="text-white/80 mb-10 text-lg">Jangan ragu untuk melaporkan ketidaksesuaian yang Anda alami. Hak Anda adalah prioritas kami.</p>
        
        @guest
            <a href="{{ route('register') }}" class="inline-block bg-white text-bpjs-dark font-black text-lg px-12 py-4 rounded-full shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300">
                Mulai Berpartisipasi
            </a>
        @else
            <a href="{{ route('pengaduan.create') }}" class="inline-block bg-white text-bpjs-dark font-black text-lg px-12 py-4 rounded-full shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300">
                Buat Laporan Baru
            </a>
        @endguest
    </div>
</div>

@endsection