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
                        <label class="block text-sm font-bold text-bpjs-dark mb-3">Lampiran Bukti Pendukung <span class="text-gray-400 font-medium">(Sangat Disarankan)</span></label>
                        <div id="upload-area" class="mt-1 flex justify-center px-6 pt-10 pb-12 border-2 border-bpjs-blue/30 border-dashed rounded-3xl hover:border-bpjs-blue bg-blue-50/30 hover:bg-blue-50/50 transition-all duration-300 group cursor-pointer shadow-inner" onclick="document.getElementById('file-upload').click()">
                            <div class="space-y-4 text-center">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm group-hover:shadow-md group-hover:-translate-y-2 transition-all duration-300">
                                    <i class="ph-fill ph-cloud-arrow-up text-4xl text-bpjs-blue group-hover:text-bpjs-dark transition-colors"></i>
                                </div>
                                <div class="flex flex-col text-sm text-gray-600 justify-center items-center gap-1.5">
                                    <span class="relative text-lg font-black text-bpjs-dark transition">
                                        Unggah Bukti Pendukung
                                    </span>
                                    <p class="text-sm font-medium text-gray-500">Pilih dokumen atau tarik dan lepas file di area ini</p>
                                </div>
                                <div class="flex flex-wrap items-center justify-center gap-2 mt-4">
                                    <span class="px-3 py-1 bg-white rounded-lg text-[10px] font-black text-gray-500 border border-gray-100 shadow-sm uppercase tracking-wider">JPG, PNG, PDF</span>
                                    <span class="px-3 py-1 bg-white rounded-lg text-[10px] font-black text-gray-500 border border-gray-100 shadow-sm uppercase tracking-wider">Maks 5 File</span>
                                    <span class="px-3 py-1 bg-white rounded-lg text-[10px] font-black text-gray-500 border border-gray-100 shadow-sm uppercase tracking-wider">Maks 20MB / file</span>
                                </div>
                            </div>
                        </div>
                        <input id="file-upload" name="lampiran[]" type="file" multiple class="sr-only" accept=".jpg,.jpeg,.png,.pdf" onchange="handleFileSelect(event)">

                        <!-- File Preview Container -->
                        <div id="file-preview-container" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 hidden">
                            <!-- Previews injected by JS -->
                        </div>

                        @error('lampiran') <span class="text-red-500 text-xs mt-1 font-medium"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</span> @enderror
                        @error('lampiran.*') <span class="text-red-500 text-xs mt-1 font-medium"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</span> @enderror
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

<script>
    const fileInput = document.getElementById('file-upload');
    const previewContainer = document.getElementById('file-preview-container');
    const uploadArea = document.getElementById('upload-area');
    
    // Store all selected files here globally so we can append new ones
    let allFilesDt = new DataTransfer();

    function handleFileSelect(event) {
        const files = event.target.files;
        
        for (let i = 0; i < files.length; i++) {
            if (allFilesDt.items.length >= 5) {
                alert('Maksimal 5 file yang dapat diunggah.');
                break;
            }
            allFilesDt.items.add(files[i]);
        }
        
        renderPreviews();
    }

    function renderPreviews() {
        previewContainer.innerHTML = '';
        const files = allFilesDt.files;
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            uploadArea.classList.add('hidden'); // Hide the big dropzone
            
            Array.from(files).forEach((file, index) => {
                let iconClass = 'ph-file-text';
                if (file.type.startsWith('image/')) iconClass = 'ph-image';
                else if (file.type === 'application/pdf') iconClass = 'ph-file-pdf';
                
                let fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                if (file.size < 1024 * 1024) {
                    fileSize = (file.size / 1024).toFixed(2) + ' KB';
                }

                const item = document.createElement('div');
                item.className = 'flex items-center p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-bpjs-blue/30 transition-all duration-300 gap-4 group/card';
                item.innerHTML = `
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-50 to-indigo-50 text-bpjs-blue rounded-xl flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="ph-fill ${iconClass} text-2xl group-hover/card:scale-110 transition-transform duration-300"></i>
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-sm font-bold text-gray-800 truncate" title="${file.name}">${file.name}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">${fileSize}</span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span class="text-[10px] font-bold text-emerald-500 flex items-center gap-1"><i class="ph-fill ph-check-circle"></i> Siap Diunggah</span>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(event, ${index})" class="w-10 h-10 bg-red-50 hover:bg-red-500 text-red-500 hover:text-white rounded-xl flex items-center justify-center transition-colors duration-300 shadow-sm flex-shrink-0" title="Hapus file">
                        <i class="ph-bold ph-trash text-lg"></i>
                    </button>
                `;
                previewContainer.appendChild(item);
            });

            // Add the "Tambah Lampiran" button if under 5 files
            if (files.length < 5) {
                const addMoreBtn = document.createElement('div');
                addMoreBtn.className = 'flex flex-col items-center justify-center gap-3 bg-gradient-to-br from-gray-50 to-gray-100 hover:from-blue-50 hover:to-indigo-50 border-2 border-dashed border-gray-200 hover:border-bpjs-blue rounded-2xl p-4 cursor-pointer transition-all duration-300 group min-h-[100px]';
                addMoreBtn.onclick = function() { fileInput.click(); };
                addMoreBtn.innerHTML = `
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                        <i class="ph-bold ph-plus text-xl text-gray-400 group-hover:text-bpjs-blue"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-500 group-hover:text-bpjs-blue">Tambah File Baru</span>
                `;
                previewContainer.appendChild(addMoreBtn);
            }
        } else {
            previewContainer.classList.add('hidden');
            uploadArea.classList.remove('hidden'); // Show the big dropzone again
        }
        
        // Sync back to the actual input
        fileInput.files = allFilesDt.files;
    }

    function removeFile(event, index) {
        event.stopPropagation();
        
        const tempDt = new DataTransfer();
        const files = allFilesDt.files;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                tempDt.items.add(files[i]);
            }
        }
        
        allFilesDt = tempDt;
        renderPreviews();
    }
</script>
@endsection