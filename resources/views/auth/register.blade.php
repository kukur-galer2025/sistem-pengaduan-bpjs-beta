@extends('layouts.app')

@section('content')
<div data-aos="zoom-in" data-aos-duration="800" class="w-full max-w-6xl mx-auto bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden my-4 md:my-10 grid grid-cols-1 lg:grid-cols-2">
    
    <div class="bg-gradient-to-br from-bpjs-dark to-slate-900 p-8 md:p-16 flex flex-col justify-center text-white border-b-4 lg:border-b-0 lg:border-r-4 border-bpjs-blue relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-bpjs-blue rounded-full mix-blend-overlay filter blur-3xl opacity-30"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-bpjs-teal rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>

        <div class="inline-flex items-center gap-2 mb-10 relative z-10">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-bpjs-blue to-bpjs-teal flex items-center justify-center shadow-lg">
                <i class="ph-fill ph-shield-check text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-white tracking-tighter">BPJS Care Portal</span>
        </div>
        
        <h3 class="text-3xl md:text-4xl font-extrabold leading-tight mb-4 md:mb-6 relative z-10">Bergabung dalam Ekosistem Transparan</h3>
        <p class="text-gray-300 leading-relaxed mb-8 md:mb-10 text-base md:text-lg relative z-10">Dengan mendaftarkan akun, Anda mendapatkan akses penuh untuk membuat pengaduan secara resmi, melampirkan bukti, dan memantau setiap tahapan respon petugas kami secara <em class="text-white font-semibold">real-time</em>.</p>
        
        <div class="flex items-center gap-4 bg-bpjs-blue/10 border border-bpjs-blue/20 rounded-2xl p-6 shadow-inner backdrop-blur-sm relative z-10">
            <div class="w-12 h-12 rounded-full bg-bpjs-blue/20 flex items-center justify-center flex-shrink-0">
                <i class="ph-fill ph-lock-key text-bpjs-blue text-2xl"></i>
            </div>
            <p class="text-sm text-gray-300 leading-relaxed font-medium">Data kependudukan Anda dilindungi dengan standar enkripsi keamanan tinggi.</p>
        </div>
    </div>

    <div class="p-6 sm:p-10 md:p-16 lg:p-20 flex flex-col justify-center bg-slate-50/50">
        <h2 class="text-3xl font-extrabold text-bpjs-dark mb-3 tracking-tight">Pendaftaran Akun Publik</h2>
        <p class="text-gray-500 mb-8">Lengkapi data diri Anda sesuai identitas resmi.</p>

        @if ($errors->any())
            <div data-aos="fade-down" class="bg-red-50 text-red-600 p-5 rounded-xl mb-6 text-sm border border-red-200 shadow-sm flex items-start gap-3">
                <i class="ph-fill ph-warning-circle text-2xl mt-0.5 flex-shrink-0"></i>
                <ul class="list-disc pl-2 space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-bpjs-dark mb-1.5">Nama Lengkap (Sesuai KTP)</label>
                <div class="relative">
                    <i class="ph ph-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full pl-11 pr-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-teal focus:border-bpjs-teal outline-none transition bg-white shadow-sm" placeholder="Nama lengkap Anda">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-bpjs-dark mb-1.5">NIK KTP (16 Digit)</label>
                    <div class="relative">
                        <i class="ph ph-identification-card absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" name="nik" value="{{ old('nik') }}" required class="w-full pl-11 pr-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-teal focus:border-bpjs-teal outline-none transition bg-white shadow-sm" placeholder="32xxxxxxxxxxxxxx">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-bpjs-dark mb-1.5">Nomor Kartu BPJS</label>
                    <div class="relative">
                        <i class="ph ph-cards absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" name="nomor_bpjs" value="{{ old('nomor_bpjs') }}" required class="w-full pl-11 pr-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-teal focus:border-bpjs-teal outline-none transition bg-white shadow-sm" placeholder="000xxxxxxxxx">
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-bpjs-dark mb-1.5">Alamat Email Aktif</label>
                <div class="relative">
                    <i class="ph ph-envelope-simple absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full pl-11 pr-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-teal focus:border-bpjs-teal outline-none transition bg-white shadow-sm" placeholder="nama@email.com">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-bpjs-dark mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <i class="ph ph-lock-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="password" name="password" required class="w-full pl-11 pr-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-teal focus:border-bpjs-teal outline-none transition bg-white shadow-sm" placeholder="••••••••">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-bpjs-dark mb-1.5">Konfirmasi Sandi</label>
                    <div class="relative">
                        <i class="ph ph-check-square-offset absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="password" name="password_confirmation" required class="w-full pl-11 pr-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-teal focus:border-bpjs-teal outline-none transition bg-white shadow-sm" placeholder="••••••••">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full btn-primary-grad py-4 text-lg shadow-xl hover:shadow-2xl hover:-translate-y-0.5 transition-all !rounded-xl mt-8 !from-bpjs-teal !to-bpjs-blue flex justify-center items-center gap-2">
                Daftar Akun Resmi <i class="ph-bold ph-paper-plane-right"></i>
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            Sudah memiliki akun resmi? <a href="{{ route('login') }}" class="text-bpjs-teal hover:underline font-bold">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection