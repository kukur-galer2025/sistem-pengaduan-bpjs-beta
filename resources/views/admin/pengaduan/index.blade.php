@extends('layouts.admin')

@section('page_title', 'Manajemen Pengaduan')

@section('content')
<div class="card-luxury">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4 overflow-x-auto pb-2">
            <a href="{{ route('admin.pengaduan.index') }}" class="px-5 py-2 rounded-full text-xs font-bold transition {{ !request('status') ? 'bg-bpjs-dark text-white shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.pengaduan.index', ['status' => 'menunggu']) }}" class="px-5 py-2 rounded-full text-xs font-bold transition {{ request('status') == 'menunggu' ? 'bg-amber-500 text-white shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                Menunggu
            </a>
            <a href="{{ route('admin.pengaduan.index', ['status' => 'diproses']) }}" class="px-5 py-2 rounded-full text-xs font-bold transition {{ request('status') == 'diproses' ? 'bg-bpjs-blue text-white shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                Diproses
            </a>
            <a href="{{ route('admin.pengaduan.index', ['status' => 'selesai']) }}" class="px-5 py-2 rounded-full text-xs font-bold transition {{ request('status') == 'selesai' ? 'bg-bpjs-teal text-white shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                Selesai
            </a>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="text-sm text-gray-400 font-medium">
                Menampilkan {{ $complaints->firstItem() ?? 0 }}-{{ $complaints->lastItem() ?? 0 }} dari {{ $complaints->total() }} Laporan
            </div>
            
            <a href="{{ route('admin.pengaduan.export-pdf', ['month' => date('m'), 'year' => date('Y')]) }}" class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-4 py-2 rounded-lg text-sm font-bold transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Ekspor Laporan (PDF)
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <th class="pb-4 px-4">Tiket & Tanggal</th>
                    <th class="pb-4 px-4">Data Pelapor</th>
                    <th class="pb-4 px-4">Subjek Keluhan</th>
                    <th class="pb-4 px-4">Status</th>
                    <th class="pb-4 px-4 text-right">Manajemen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($complaints as $complaint)
                <tr class="group hover:bg-gray-50/50 transition">
                    <td class="py-6 px-4">
                        <div class="font-black text-bpjs-dark text-sm">{{ $complaint->kode_tiket }}</div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase mt-1">{{ $complaint->created_at->format('d/m/Y • H:i') }}</div>
                    </td>
                    <td class="py-6 px-4">
                        <div class="text-sm font-bold text-gray-700">{{ $complaint->user->name }}</div>
                        <div class="text-xs text-gray-400">NIK: {{ $complaint->user->nik }}</div>
                    </td>
                    <td class="py-6 px-4 max-w-xs">
                        <div class="text-xs font-bold text-bpjs-blue mb-1 uppercase tracking-tighter">{{ $complaint->category->nama_kategori }}</div>
                        <div class="text-sm font-medium text-gray-600 truncate">{{ $complaint->judul }}</div>
                    </td>
                    <td class="py-6 px-4">
                        @if($complaint->status == 'menunggu')
                            <span class="inline-flex px-3 py-1 rounded-md text-[10px] font-black uppercase bg-amber-100 text-amber-700 border border-amber-200">Pending</span>
                        @elseif($complaint->status == 'diproses')
                            <span class="inline-flex px-3 py-1 rounded-md text-[10px] font-black uppercase bg-blue-100 text-blue-700 border border-blue-200">On Process</span>
                        @elseif($complaint->status == 'selesai')
                            <span class="inline-flex px-3 py-1 rounded-md text-[10px] font-black uppercase bg-bpjs-teal/10 text-bpjs-teal border border-bpjs-teal/20">Resolved</span>
                        @else
                            <span class="inline-flex px-3 py-1 rounded-md text-[10px] font-black uppercase bg-red-100 text-red-700 border border-red-200">Rejected</span>
                        @endif
                    </td>
                    <td class="py-6 px-4 text-right">
                        <a href="{{ route('admin.pengaduan.show', $complaint->id) }}" class="btn-primary-grad !px-4 !py-2 !text-xs !rounded-lg shadow-sm">
                            Kelola Laporan
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20 text-center">
                        <div class="text-gray-300 mb-2">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-400">Tidak ada pengaduan yang ditemukan untuk kriteria ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-10">
        {{ $complaints->links() }}
    </div>
</div>
@endsection