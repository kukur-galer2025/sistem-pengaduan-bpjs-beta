@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <a href="{{ route('pengaduan.index') }}" data-aos="fade-right" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-bpjs-blue transition bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 w-max">
            <i class="ph-bold ph-arrow-left text-lg"></i>
            Daftar Riwayat
        </a>
        
        <div data-aos="fade-left" class="bg-white px-5 py-2.5 rounded-xl border border-gray-200 shadow-sm inline-flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-bpjs-blue/10 flex items-center justify-center">
                <i class="ph-fill ph-ticket text-bpjs-blue text-lg"></i>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">KODE TIKET</span>
                <span class="block text-lg font-black text-bpjs-dark leading-none">{{ $complaint->kode_tiket }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div data-aos="fade-up" class="card-luxury p-0 overflow-hidden bg-white rounded-3xl shadow-xl border border-gray-100">
                <div class="p-6 md:p-10 border-b border-gray-100 relative">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <span class="px-4 py-1.5 bg-bpjs-blue/10 text-bpjs-blue text-xs font-bold rounded-full border border-bpjs-blue/20 flex items-center gap-1.5">
                            <i class="ph-fill ph-tag"></i> {{ $complaint->category->nama_kategori }}
                        </span>
                        <span class="text-sm text-gray-500 font-medium flex items-center gap-1.5 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                            <i class="ph-fill ph-calendar-blank text-gray-400"></i>
                            {{ $complaint->created_at->translatedFormat('d F Y, H:i') }} WIB
                        </span>
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-extrabold text-bpjs-dark mb-8 leading-tight">
                        {{ $complaint->judul }}
                    </h1>
                    
                    <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed">
                        <h4 class="text-sm font-bold text-bpjs-dark uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="ph-fill ph-text-align-left text-bpjs-teal text-lg"></i> Rincian Kronologi
                        </h4>
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            <p class="whitespace-pre-wrap text-gray-700">{{ $complaint->kronologi }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 p-6 md:p-10">
                    <h4 class="text-sm font-bold text-bpjs-dark uppercase tracking-widest mb-5 flex items-center gap-2">
                        <i class="ph-fill ph-paperclip text-bpjs-teal text-lg"></i> Dokumen Lampiran
                    </h4>
                    
                    @if($complaint->lampiran_path)
                        <div class="flex flex-col sm:flex-row sm:items-center p-4 bg-white border border-gray-200 rounded-2xl shadow-sm max-w-lg gap-4 transition hover:border-bpjs-blue hover:shadow-md">
                            <div class="w-14 h-14 bg-blue-50 text-bpjs-blue rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="ph-fill ph-file-text text-3xl"></i>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-sm font-bold text-gray-800 truncate">Bukti_Lampiran_Tiket_{{ substr($complaint->kode_tiket, -4) }}</p>
                                <p class="text-xs text-gray-500 font-medium mt-0.5">Disertakan oleh Pelapor</p>
                            </div>
                            <a href="{{ asset('storage/' . $complaint->lampiran_path) }}" target="_blank" class="px-5 py-2.5 bg-bpjs-dark hover:bg-bpjs-blue text-white text-sm font-bold rounded-xl transition shadow flex items-center justify-center gap-2 flex-shrink-0">
                                <i class="ph-bold ph-eye text-lg"></i> Lihat
                            </a>
                        </div>
                    @else
                        <div class="flex items-center gap-3 text-gray-400 italic text-sm bg-white p-5 rounded-2xl border border-gray-100 border-dashed">
                            <i class="ph-fill ph-prohibit text-2xl opacity-50"></i>
                            <span class="font-medium">Tidak ada dokumen lampiran yang disertakan pada tiket laporan ini.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div data-aos="fade-left" data-aos-delay="200" class="card-luxury sticky top-28 bg-white rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8">
                <h3 class="text-xl font-extrabold text-bpjs-dark mb-8 flex items-center gap-2 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-full bg-bpjs-teal/10 flex items-center justify-center text-bpjs-teal">
                        <i class="ph-fill ph-pulse"></i>
                    </div>
                    Pelacakan Status
                </h3>
                
                <div class="relative pl-7 border-l-2 border-gray-100 space-y-8 ml-2">
                    @foreach($complaint->histories as $index => $history)
                        <div class="relative group">
                            <div class="absolute -left-[38px] bg-white py-2">
                                <div class="w-6 h-6 rounded-full border-[3px] flex items-center justify-center
                                    @if($index == 0) 
                                        border-bpjs-blue bg-white shadow-[0_0_15px_rgba(17,138,178,0.3)]
                                    @else 
                                        border-gray-200 bg-gray-100 
                                    @endif
                                ">
                                    @if($index == 0)
                                        <div class="w-2.5 h-2.5 bg-bpjs-blue rounded-full animate-pulse"></div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 group-hover:border-bpjs-blue/30 group-hover:bg-blue-50/20 transition-colors">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="ph-fill ph-clock text-gray-400 text-sm"></i>
                                    <span class="text-xs font-bold text-gray-500">
                                        {{ $history->created_at->translatedFormat('d M Y, H:i') }}
                                    </span>
                                </div>
                                
                                <div class="text-sm font-extrabold mb-2 uppercase tracking-wide
                                    @if($history->status == 'menunggu') text-amber-500
                                    @elseif($history->status == 'diproses') text-bpjs-blue
                                    @elseif($history->status == 'selesai') text-bpjs-teal
                                    @else text-red-500 @endif
                                ">
                                    {{ $history->status }}
                                </div>
                                
                                <p class="text-sm text-gray-600 font-medium leading-relaxed mb-3">
                                    {{ $history->catatan }}
                                </p>

                                @if($history->petugas)
                                    <div class="flex items-center gap-2 pt-3 border-t border-gray-200/60">
                                        <div class="w-6 h-6 bg-bpjs-dark rounded-full flex items-center justify-center text-white text-[10px] font-bold shadow-sm">
                                            {{ strtoupper(substr($history->petugas->name, 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase leading-none">Diperbarui Oleh</span>
                                            <span class="text-xs font-bold text-bpjs-dark leading-tight">{{ $history->petugas->name }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 pt-3 border-t border-gray-200/60">
                                        <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-[10px] font-bold">
                                            <i class="ph-fill ph-robot"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-gray-400 uppercase">Sistem Otomatis</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection