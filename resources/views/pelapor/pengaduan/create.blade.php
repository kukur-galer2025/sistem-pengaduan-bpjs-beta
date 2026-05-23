@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('pengaduan.index') }}" data-aos="fade-right" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-bpjs-blue transition mb-6 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 w-max">
        <i class="ph-bold ph-arrow-left text-lg"></i>
        Kembali ke Riwayat
    </a>

    <div data-aos="fade-up" data-aos-duration="800" class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-bpjs-teal opacity-5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

        <div class="bg-gradient-to-r from-bpjs-dark to-slate-800 p-6 md:p-10 text-white border-b-4 border-bpjs-teal relative z-10 overflow-hidden">
            <i class="ph-fill ph-note-pencil absolute -right-4 -top-4 text-8xl text-white/10 -rotate-12"></i>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight mb-2 flex items-center gap-2 md:gap-3">
                <i class="ph-fill ph-file-plus text-bpjs-teal"></i> Buat Tiket Pengaduan
            </h2>
            <p class="text-gray-300 text-xs md:text-sm max-w-2xl leading-relaxed">Harap isi formulir di bawah ini dengan data yang valid dan kronologi yang jelas agar petugas dapat memprosesnya dengan cepat dan akurat.</p>
        </div>

        <div class="p-6 md:p-10 relative z-10">
            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-bpjs-dark mb-2">Kategori Masalah <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="ph ph-list-dashes absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl pointer-events-none"></i>
                            <select name="category_id" required class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-gray-700 font-medium appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Pilih Kategori Layanan Terkait --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                        @error('category_id') <span class="text-red-500 text-xs mt-1 font-medium"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-bpjs-dark mb-2">Ringkasan Keluhan (Judul) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="ph ph-text-t absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                            <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Penolakan Rujukan di RS A karena kuota penuh" class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-gray-700 font-medium">
                        </div>
                        @error('judul') <span class="text-red-500 text-xs mt-1 font-medium"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-bpjs-dark mb-2">Kronologi Kejadian <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="ph ph-text-align-left absolute left-4 top-5 text-gray-400 text-xl"></i>
                            <textarea name="kronologi" rows="6" required placeholder="Jelaskan secara detail kejadian yang Anda alami. Cantumkan tanggal, tempat, dan nama petugas terkait (jika ada)..." class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-bpjs-blue focus:bg-white outline-none transition text-gray-700 font-medium leading-relaxed">{{ old('kronologi') }}</textarea>
                        </div>
                        @error('kronologi') <span class="text-red-500 text-xs mt-1 font-medium"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-bpjs-dark mb-2">Lampiran Bukti <span class="text-gray-400 font-medium">(Opsional, namun sangat disarankan)</span></label>
                        <div class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-gray-300 border-dashed rounded-2xl hover:border-bpjs-blue hover:bg-blue-50/50 transition-all group bg-gray-50 cursor-pointer" onclick="document.getElementById('file-upload').click()">
                            <div class="space-y-2 text-center">
                                <i class="ph-fill ph-file-arrow-up text-5xl text-gray-300 group-hover:text-bpjs-blue transition-colors mb-2 block transform group-hover:-translate-y-1"></i>
                                <div class="flex text-sm text-gray-600 justify-center items-center gap-1">
                                    <span class="relative font-bold text-bpjs-blue group-hover:text-bpjs-dark transition">
                                        Pilih Dokumen
                                        <input id="file-upload" name="lampiran" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf">
                                    </span>
                                    <p class="pl-1">atau tarik dan lepas di area ini</p>
                                </div>
                                <p class="text-xs text-gray-400 font-medium">Format JPG, PNG, atau PDF (Maksimal 20 MB)</p>
                            </div>
                        </div>
                        @error('lampiran') <span class="text-red-500 text-xs mt-1 font-medium"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-start gap-3 bg-blue-50 p-4 rounded-xl max-w-sm border border-blue-100">
                        <i class="ph-fill ph-info text-bpjs-blue text-xl flex-shrink-0 mt-0.5"></i>
                        <p class="text-xs text-gray-600 font-medium leading-relaxed">Dengan menekan tombol kirim, Anda mengonfirmasi bahwa seluruh data dan bukti yang diberikan adalah benar dan dapat dipertanggungjawabkan.</p>
                    </div>
                    
                    <button type="submit" class="w-full md:w-auto btn-primary-grad px-8 py-4 !rounded-xl text-lg font-bold shadow-xl hover:shadow-2xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        Kirim Laporan Resmi <i class="ph-bold ph-paper-plane-right text-xl"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection