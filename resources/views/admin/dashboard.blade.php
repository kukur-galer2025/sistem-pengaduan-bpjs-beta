@extends('layouts.admin')

@section('page_title', 'Executive Dashboard')

@section('content')
<div class="space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card-luxury !p-6 border-l-4 border-bpjs-blue">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pengaduan</p>
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-black text-bpjs-dark">{{ $totalPengaduan }}</h3>
                <div class="p-2 bg-bpjs-blue/10 rounded-lg text-bpjs-blue">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
        </div>

        <div class="card-luxury !p-6 border-l-4 border-amber-500">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Menunggu Verifikasi</p>
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-black text-amber-600">{{ $menunggu }}</h3>
                <div class="p-2 bg-amber-100 rounded-lg text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="card-luxury !p-6 border-l-4 border-bpjs-teal">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Sedang Diproses</p>
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-black text-bpjs-teal">{{ $diproses }}</h3>
                <div class="p-2 bg-bpjs-teal/10 rounded-lg text-bpjs-teal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
            </div>
        </div>

        <div class="card-luxury !p-6 border-l-4 border-gray-900">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Laporan Selesai</p>
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-black text-gray-900">{{ $selesai }}</h3>
                <div class="p-2 bg-gray-100 rounded-lg text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="card-luxury">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="text-xl font-bold text-bpjs-dark">Laporan Terbaru</h4>
                <p class="text-sm text-gray-400">5 laporan masuk terakhir yang membutuhkan perhatian.</p>
            </div>
            <a href="{{ route('admin.pengaduan.index') }}" class="text-sm font-bold text-bpjs-blue hover:underline">Lihat Semua Data &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="pb-4 px-2">Pelapor</th>
                        <th class="pb-4 px-2">Kategori</th>
                        <th class="pb-4 px-2">Judul Keluhan</th>
                        <th class="pb-4 px-2">Status</th>
                        <th class="pb-4 px-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentComplaints as $complaint)
                    <tr class="group hover:bg-gray-50/50 transition">
                        <td class="py-5 px-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-bpjs-blue/10 flex items-center justify-center text-bpjs-blue font-bold text-xs">
                                    {{ substr($complaint->user->name, 0, 1) }}
                                </div>
                                <div class="text-sm font-bold text-bpjs-dark">{{ $complaint->user->name }}</div>
                            </div>
                        </td>
                        <td class="py-5 px-2">
                            <span class="text-xs font-semibold text-gray-500">{{ $complaint->category->nama_kategori }}</span>
                        </td>
                        <td class="py-5 px-2">
                            <div class="text-sm font-medium text-gray-700 truncate max-w-xs">{{ $complaint->judul }}</div>
                        </td>
                        <td class="py-5 px-2">
                            @if($complaint->status == 'menunggu')
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block mr-2"></span>
                            @elseif($complaint->status == 'diproses')
                                <span class="w-2.5 h-2.5 rounded-full bg-bpjs-blue inline-block mr-2"></span>
                            @else
                                <span class="w-2.5 h-2.5 rounded-full bg-bpjs-teal inline-block mr-2"></span>
                            @endif
                            <span class="text-xs font-bold uppercase tracking-tight text-gray-600">{{ $complaint->status }}</span>
                        </td>
                        <td class="py-5 px-2 text-right">
                            <a href="{{ route('admin.pengaduan.show', $complaint->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-bpjs-blue hover:border-bpjs-blue shadow-sm transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection