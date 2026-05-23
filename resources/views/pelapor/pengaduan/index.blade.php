@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div data-aos="fade-down" class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-bpjs-dark tracking-tight flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-bpjs-teal/10 flex items-center justify-center text-bpjs-teal">
                    <i class="ph-fill ph-clock-counter-clockwise"></i>
                </div>
                Riwayat Pengaduan
            </h2>
            <p class="text-gray-500 mt-2 font-medium">Pantau status tindak lanjut dari laporan yang telah Anda kirimkan ke sistem.</p>
        </div>
        <a href="{{ route('pengaduan.create') }}" class="btn-primary-grad py-3.5 px-6 !rounded-xl text-sm font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <i class="ph-bold ph-plus text-lg"></i> Buat Laporan Baru
        </a>
    </div>

    @if(session('success'))
        <div data-aos="fade-down" class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm border border-green-200 shadow-sm flex items-start gap-3">
            <i class="ph-fill ph-check-circle text-xl mt-0.5"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-5">
        @forelse ($complaints as $complaint)
            <a href="{{ route('pengaduan.show', $complaint->id) }}" 
               data-aos="fade-up" 
               data-aos-delay="{{ $loop->iteration * 50 }}" 
               class="block bg-white border border-gray-100 rounded-2xl p-5 md:p-6 shadow-sm hover:shadow-xl hover:border-bpjs-blue/30 transition-all duration-300 group relative overflow-hidden">
                
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-bpjs-blue to-bpjs-teal opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 pl-2">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <span class="text-xs font-extrabold text-gray-500 tracking-wider flex items-center gap-1.5">
                                <i class="ph-fill ph-ticket text-gray-400"></i> {{ $complaint->kode_tiket }}
                            </span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                            <span class="text-[11px] font-bold text-bpjs-blue bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-md flex items-center gap-1.5 uppercase tracking-wide">
                                <i class="ph-fill ph-tag"></i> {{ $complaint->category->nama_kategori }}
                            </span>
                        </div>
                        <h3 class="text-xl font-extrabold text-bpjs-dark group-hover:text-bpjs-blue transition-colors mb-2">{{ $complaint->judul }}</h3>
                        <p class="text-gray-500 text-sm line-clamp-2 font-medium leading-relaxed pr-4">{{ $complaint->kronologi }}</p>
                    </div>
                    
                    <div class="flex flex-col items-start md:items-end gap-3 min-w-[180px] md:border-l border-gray-100 md:pl-6 mt-4 md:mt-0">
                        @if($complaint->status == 'menunggu')
                            <span class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200 flex items-center gap-2 w-max shadow-sm">
                                <i class="ph-fill ph-hourglass-high text-base"></i> Menunggu Verifikasi
                            </span>
                        @elseif($complaint->status == 'diproses')
                            <span class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-50 text-bpjs-blue border border-blue-200 flex items-center gap-2 w-max shadow-sm">
                                <i class="ph-fill ph-arrows-clockwise text-base animate-spin-slow"></i> Sedang Diproses
                            </span>
                        @elseif($complaint->status == 'selesai')
                            <span class="px-4 py-2 rounded-xl text-xs font-bold bg-teal-50 text-bpjs-teal border border-teal-200 flex items-center gap-2 w-max shadow-sm">
                                <i class="ph-fill ph-check-circle text-base"></i> Selesai
                            </span>
                        @else
                            <span class="px-4 py-2 rounded-xl text-xs font-bold bg-red-50 text-red-600 border border-red-200 flex items-center gap-2 w-max shadow-sm">
                                <i class="ph-fill ph-x-circle text-base"></i> Ditolak
                            </span>
                        @endif
                        
                        <span class="text-xs text-gray-400 font-bold flex items-center gap-1.5 mt-1">
                            <i class="ph-fill ph-clock"></i> {{ $complaint->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div data-aos="fade-up" class="bg-white border border-gray-100 rounded-3xl p-16 text-center shadow-sm relative overflow-hidden mt-8">
                <div class="absolute inset-0 bg-gradient-to-b from-gray-50/50 to-white z-0"></div>
                <div class="relative z-10">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-100 shadow-inner">
                        <i class="ph-fill ph-folder-open text-5xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-bpjs-dark mb-3">Belum Ada Pengaduan</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-8 font-medium leading-relaxed">Anda belum pernah membuat laporan pengaduan. Sistem akan menyimpan dan melacak seluruh riwayat laporan Anda di halaman ini.</p>
                    <a href="{{ route('pengaduan.create') }}" class="btn-primary-grad py-3.5 px-8 !rounded-xl text-sm font-bold inline-flex items-center gap-2 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        <i class="ph-bold ph-plus text-lg"></i> Buat Pengaduan Perdana
                    </a>
                </div>
            </div>
        @endforelse

        @if($complaints->hasPages())
            <div class="mt-10 bg-white p-4 rounded-2xl shadow-sm border border-gray-100" data-aos="fade-up">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .animate-spin-slow {
        animation: spin 3s linear infinite;
    }
</style>
@endsection