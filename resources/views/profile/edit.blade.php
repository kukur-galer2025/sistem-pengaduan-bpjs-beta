@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb / Back Button -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('pengaduan.index') }}" data-aos="fade-right" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-bpjs-blue transition bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
            <i class="ph-bold ph-arrow-left text-lg"></i>
            Kembali ke Riwayat
        </a>

        <div data-aos="fade-left" class="hidden sm:inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-bpjs-blue/10 text-bpjs-blue border border-bpjs-blue/20 text-xs font-bold uppercase tracking-wider">
            <i class="ph-fill ph-shield-check text-base"></i> Akun Terverifikasi
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div data-aos="fade-down" class="bg-bpjs-teal/15 text-bpjs-dark border border-bpjs-teal/30 p-5 rounded-2xl mb-8 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-bpjs-teal flex items-center justify-center text-white shrink-0 shadow">
                <i class="ph-bold ph-check text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm">Berhasil Memperbarui Data</h4>
                <p class="text-xs text-gray-600 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div data-aos="fade-down" class="bg-red-50 text-red-700 border border-red-200 p-5 rounded-2xl mb-8 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center text-white shrink-0 shadow">
                <i class="ph-bold ph-warning-circle text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm">Terjadi Kesalahan Validasi</h4>
                <p class="text-xs text-red-600 mt-0.5">{{ $errors->first() }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left Column: User Summary Card -->
        <div data-aos="fade-up" data-aos-duration="600" class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 text-center relative overflow-hidden">
                <div class="absolute -top-16 -right-16 w-36 h-36 bg-bpjs-teal/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-bpjs-blue/10 rounded-full blur-2xl"></div>

                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-bpjs-dark via-slate-800 to-bpjs-blue flex items-center justify-center text-white text-3xl font-black shadow-xl shadow-bpjs-dark/20 border-4 border-white mb-4">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>

                    <h3 class="text-xl font-extrabold text-bpjs-dark">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5 font-medium">{{ $user->email }}</p>

                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                            <i class="ph-fill {{ $user->role === 'admin' ? 'ph-crown' : 'ph-user-check' }}"></i>
                            {{ $user->role === 'admin' ? 'Administrator' : 'Pelapor / Peserta BPJS' }}
                        </span>
                    </div>

                    <div class="w-full h-px bg-gray-100 my-6"></div>

                    <div class="w-full space-y-3.5 text-left text-xs">
                        @if($user->nik)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-gray-100">
                            <span class="text-gray-400 font-bold uppercase tracking-wider">NIK KTP</span>
                            <span class="font-extrabold text-slate-700 font-mono">{{ $user->nik }}</span>
                        </div>
                        @endif

                        @if($user->nomor_bpjs)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-gray-100">
                            <span class="text-gray-400 font-bold uppercase tracking-wider">No. BPJS</span>
                            <span class="font-extrabold text-bpjs-blue font-mono">{{ $user->nomor_bpjs }}</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-gray-100">
                            <span class="text-gray-400 font-bold uppercase tracking-wider">Bergabung</span>
                            <span class="font-bold text-slate-600">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="bg-gradient-to-br from-bpjs-dark to-slate-800 text-white p-6 rounded-3xl shadow-xl border border-slate-700 relative overflow-hidden">
                <i class="ph-fill ph-shield-check text-7xl text-white/5 absolute -right-3 -bottom-3"></i>
                <h4 class="text-sm font-bold flex items-center gap-2 mb-2 text-bpjs-teal">
                    <i class="ph-fill ph-info"></i> Keamanan Akun
                </h4>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Pastikan data NIK dan Nomor Kartu BPJS Anda sesuai dengan dokumen asli agar proses validasi laporan pengaduan dapat berjalan cepat tanpa hambatan.
                </p>
            </div>
        </div>

        <!-- Right Column: Edit Profile Form -->
        <div data-aos="fade-up" data-aos-duration="800" class="lg:col-span-2">
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Section 1: Informasi Identitas -->
                <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-gray-100 relative">
                    <div class="flex items-center gap-3 pb-6 mb-8 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-2xl bg-bpjs-blue/10 flex items-center justify-center text-bpjs-blue">
                            <i class="ph-fill ph-user-circle text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-bpjs-dark">Informasi Akun & Identitas</h2>
                            <p class="text-xs text-gray-500">Perbarui data nama lengkap, kontak, serta data kepesertaan BPJS Anda.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-bpjs-dark uppercase tracking-wider mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="ph ph-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-sm font-medium text-gray-700" placeholder="Masukkan nama lengkap Anda">
                            </div>
                            @error('name') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-bpjs-dark uppercase tracking-wider mb-2">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="ph ph-envelope-simple absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-sm font-medium text-gray-700" placeholder="nama@email.com">
                            </div>
                            @error('email') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- NIK KTP -->
                        <div>
                            <label class="block text-xs font-bold text-bpjs-dark uppercase tracking-wider mb-2">
                                NIK KTP (16 Digit) @if($user->role === 'pelapor')<span class="text-red-500">*</span>@endif
                            </label>
                            <div class="relative">
                                <i class="ph ph-identification-card absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" maxlength="16" {{ $user->role === 'pelapor' ? 'required' : '' }} class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-sm font-medium text-gray-700 font-mono tracking-wider" placeholder="Contoh: 3201234567890001">
                            </div>
                            @error('nik') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nomor Kartu BPJS -->
                        <div>
                            <label class="block text-xs font-bold text-bpjs-dark uppercase tracking-wider mb-2">
                                No. Kartu BPJS (13 Digit) @if($user->role === 'pelapor')<span class="text-red-500">*</span>@endif
                            </label>
                            <div class="relative">
                                <i class="ph ph-credit-card absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="text" name="nomor_bpjs" value="{{ old('nomor_bpjs', $user->nomor_bpjs) }}" maxlength="13" {{ $user->role === 'pelapor' ? 'required' : '' }} class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-sm font-medium text-gray-700 font-mono tracking-wider" placeholder="Contoh: 0001234567890">
                            </div>
                            @error('nomor_bpjs') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Ganti Password -->
                <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-gray-100 relative">
                    <div class="flex items-center gap-3 pb-6 mb-8 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                            <i class="ph-fill ph-lock-key text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-bpjs-dark">Ubah Kata Sandi</h2>
                            <p class="text-xs text-gray-500">Kosongkan bagian ini jika Anda tidak ingin mengubah kata sandi.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Password Saat Ini -->
                        <div>
                            <label class="block text-xs font-bold text-bpjs-dark uppercase tracking-wider mb-2">
                                Kata Sandi Saat Ini
                            </label>
                            <div class="relative">
                                <i class="ph ph-lock-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="password" name="current_password" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-sm font-medium text-gray-700" placeholder="Masukkan kata sandi saat ini jika ingin mengubah">
                            </div>
                            @error('current_password') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password Baru -->
                            <div>
                                <label class="block text-xs font-bold text-bpjs-dark uppercase tracking-wider mb-2">
                                    Kata Sandi Baru (Min. 8 Karakter)
                                </label>
                                <div class="relative">
                                    <i class="ph ph-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                    <input type="password" name="new_password" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-sm font-medium text-gray-700" placeholder="Minimal 8 karakter">
                                </div>
                                @error('new_password') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div>
                                <label class="block text-xs font-bold text-bpjs-dark uppercase tracking-wider mb-2">
                                    Ulangi Kata Sandi Baru
                                </label>
                                <div class="relative">
                                    <i class="ph ph-shield-check absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                    <input type="password" name="new_password_confirmation" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-sm font-medium text-gray-700" placeholder="Ulangi kata sandi baru">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-2">
                    <a href="{{ route('pengaduan.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-100 transition text-center">
                        Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto btn-primary-grad !px-10 !py-4 shadow-xl flex items-center justify-center gap-2.5">
                        <i class="ph-bold ph-floppy-disk-back text-lg"></i>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
