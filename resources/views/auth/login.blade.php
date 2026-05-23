@extends('layouts.app')

@section('content')
<div data-aos="zoom-in" data-aos-duration="800" class="w-full max-w-6xl mx-auto bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden my-4 md:my-10 grid grid-cols-1 lg:grid-cols-2">
    
    <div class="bg-bpjs-dark p-8 md:p-16 flex flex-col justify-center text-white border-b-4 lg:border-b-0 lg:border-r-4 border-bpjs-teal relative overflow-hidden">
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-bpjs-teal rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-bpjs-blue rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

        <div class="inline-flex items-center gap-2 mb-10 relative z-10">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-bpjs-blue to-bpjs-teal flex items-center justify-center shadow-lg">
                <i class="ph-fill ph-shield-check text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-white tracking-tighter">BPJS Care Portal</span>
        </div>
        
        <h3 class="text-3xl md:text-4xl font-extrabold leading-tight mb-4 md:mb-6 relative z-10">Akses Portal Pengaduan Resmi</h3>
        <p class="text-gray-300 leading-relaxed mb-8 md:mb-10 text-base md:text-lg relative z-10">Silakan masuk dengan akun terverifikasi Anda untuk membuat laporan baru atau memantau status tindak lanjut dari laporan sebelumnya.</p>
        
        <div class="border border-slate-700/50 rounded-2xl p-6 bg-black/20 backdrop-blur-sm relative z-10">
            <div class="flex gap-4 items-start">
                <i class="ph-fill ph-quotes text-bpjs-teal text-3xl opacity-50"></i>
                <p class="text-sm text-gray-300 italic font-medium leading-relaxed">"Peningkatan layanan bermula dari suara Anda. Terima kasih telah berkontribusi secara aktif."</p>
            </div>
        </div>
    </div>

    <div class="p-6 sm:p-10 md:p-16 lg:p-20 flex flex-col justify-center bg-slate-50/50">
        <h2 class="text-3xl font-extrabold text-bpjs-dark mb-3 tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-gray-500 mb-10">Masuk untuk melanjutkan ke dashboard pribadi Anda.</p>

        @if (session('status'))
            <div data-aos="fade-down" class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm border border-green-200 shadow-sm flex items-start gap-3">
                <i class="ph-fill ph-check-circle text-xl mt-0.5"></i>
                <p class="font-medium">{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div data-aos="fade-down" class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-200 shadow-sm flex items-start gap-3">
                <i class="ph-fill ph-warning-circle text-xl mt-0.5"></i>
                <p class="font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-bpjs-dark mb-1.5">Alamat Email Resmi</label>
                <div class="relative">
                    <i class="ph ph-envelope-simple absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full pl-11 pr-5 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:border-bpjs-blue outline-none transition bg-white shadow-sm" placeholder="nama@domain.com">
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="block text-sm font-semibold text-bpjs-dark">Kata Sandi</label>
                    <a href="#" class="text-xs text-bpjs-blue hover:underline font-medium">Lupa Password?</a>
                </div>
                <div class="relative">
                    <i class="ph ph-lock-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="password" name="password" required class="w-full pl-11 pr-5 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:border-bpjs-blue outline-none transition bg-white shadow-sm" placeholder="••••••••">
                </div>
            </div>
            
            <div class="flex items-center pt-2">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-bpjs-blue border-gray-300 rounded focus:ring-bpjs-blue cursor-pointer">
                <label for="remember" class="ml-2.5 text-sm text-gray-600 cursor-pointer font-medium">Ingat sesi saya di perangkat ini</label>
            </div>
            
            <button type="submit" class="w-full btn-primary-grad py-4 text-lg shadow-xl hover:shadow-2xl hover:-translate-y-0.5 transition-all !rounded-xl mt-6 flex justify-center items-center gap-2">
                Masuk ke Portal <i class="ph-bold ph-arrow-right"></i>
            </button>
        </form>

        <p class="mt-10 text-center text-sm text-gray-600">
            Belum memiliki akun resmi? <a href="{{ route('register') }}" class="text-bpjs-blue hover:underline font-bold">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection