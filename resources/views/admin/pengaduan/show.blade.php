@extends('layouts.admin')

@section('page_title', 'Detail Pengaduan')

@section('content')
<div class="max-w-6xl space-y-8">
    
    @if(session('success'))
        <div class="bg-bpjs-teal text-white p-4 rounded-xl shadow-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="lg:col-span-2 space-y-8">
            <div class="card-luxury !p-0 overflow-hidden">
                <div class="p-6 md:p-10 border-b border-gray-100 bg-gradient-to-br from-white to-gray-50/50">
                    <div class="flex items-center justify-between mb-8">
                        <div class="px-4 py-1.5 bg-bpjs-dark text-white text-[10px] font-black rounded-lg tracking-widest uppercase">
                            {{ $complaint->kode_tiket }}
                        </div>
                        <div class="text-xs text-gray-400 font-bold uppercase">Masuk: {{ $complaint->created_at->translatedFormat('d F Y') }}</div>
                    </div>

                    <h2 class="text-3xl font-black text-bpjs-dark mb-4 leading-tight">{{ $complaint->judul }}</h2>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-10">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-bpjs-teal/10 flex items-center justify-center text-bpjs-teal">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span class="font-bold">{{ $complaint->user->name }}</span>
                        </div>
                        <span class="text-gray-300">|</span>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-bpjs-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <span class="font-semibold">{{ $complaint->category->nama_kategori }}</span>
                        </div>
                    </div>

                    <div class="prose prose-slate max-w-none">
                        <p class="text-gray-600 leading-relaxed italic border-l-4 border-gray-100 pl-6 py-2">
                            "{{ $complaint->kronologi }}"
                        </p>
                    </div>
                </div>

                <div class="p-6 md:p-10 bg-gray-50/50">
                    <h5 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Lampiran Bukti Pelapor</h5>
                    @if($complaint->lampiran_path)
                        <div class="inline-flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                            <div class="p-3 bg-bpjs-blue/10 text-bpjs-blue rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-700">Dokumen_Lampiran.jpg</p>
                                <a href="{{ asset('storage/' . $complaint->lampiran_path) }}" target="_blank" class="text-[10px] font-black text-bpjs-blue uppercase hover:underline">Buka File Asli</a>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak ada lampiran disertakan.</p>
                    @endif
                </div>
            </div>

            <div class="card-luxury">
                <h5 class="text-sm font-black text-bpjs-dark uppercase tracking-widest mb-8">Riwayat Penanganan Laporan</h5>
                <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                    @foreach($complaint->histories as $history)
                    <div class="relative flex items-start gap-10">
                        <div class="absolute left-0 mt-1 w-10 h-10 rounded-full border-4 border-white bg-gray-100 flex items-center justify-center z-10 shadow-sm
                            @if($history->status == 'menunggu') text-amber-500
                            @elseif($history->status == 'diproses') text-bpjs-blue
                            @else text-bpjs-teal @endif
                        ">
                            <div class="w-2 h-2 rounded-full currentColor bg-current"></div>
                        </div>
                        <div class="ml-14 flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-xs font-black uppercase tracking-tight
                                    @if($history->status == 'menunggu') text-amber-600
                                    @elseif($history->status == 'diproses') text-bpjs-blue
                                    @else text-bpjs-teal @endif
                                ">Status: {{ $history->status }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $history->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl text-sm text-gray-600">
                                {{ $history->catatan }}
                                @if($history->petugas)
                                    <div class="mt-4 pt-4 border-t border-gray-200/50 flex items-center gap-2">
                                        <div class="w-5 h-5 bg-bpjs-dark rounded-full flex items-center justify-center text-[8px] text-white font-bold">{{ substr($history->petugas->name, 0, 1) }}</div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Oleh Petugas: {{ $history->petugas->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Diskusi Tiket -->
            <div class="card-luxury bg-white rounded-3xl shadow-xl border border-gray-100 mt-8 overflow-hidden">
                <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-xl font-extrabold text-bpjs-dark flex items-center gap-2">
                        <svg class="w-6 h-6 text-bpjs-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Diskusi Tiket
                    </h3>
                </div>
                
                <div class="p-6 md:p-8 space-y-6 max-h-[500px] overflow-y-auto bg-slate-50/50">
                    @forelse($complaint->comments as $comment)
                        @if($comment->user_id == Auth::id())
                            <!-- My Comment -->
                            <div class="flex flex-col items-end w-full">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold text-gray-400">{{ $comment->created_at->format('d M H:i') }}</span>
                                    <span class="text-xs font-bold text-bpjs-dark">Anda</span>
                                </div>
                                <div class="bg-bpjs-dark text-white px-5 py-3 rounded-2xl rounded-tr-sm shadow-sm max-w-[85%] sm:max-w-[70%]">
                                    <p class="text-sm">{{ $comment->body }}</p>
                                </div>
                            </div>
                        @else
                            <!-- Other Comment -->
                            <div class="flex flex-col items-start w-full">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-bpjs-dark">{{ $comment->user->name }} ({{ $comment->user->role }})</span>
                                    <span class="text-[10px] font-bold text-gray-400">{{ $comment->created_at->format('d M H:i') }}</span>
                                </div>
                                <div class="bg-white border border-gray-200 text-gray-700 px-5 py-3 rounded-2xl rounded-tl-sm shadow-sm max-w-[85%] sm:max-w-[70%]">
                                    <p class="text-sm">{{ $comment->body }}</p>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-gray-500 font-medium">Belum ada diskusi untuk tiket ini.</p>
                        </div>
                    @endforelse
                </div>

                @if($complaint->status != 'selesai' && $complaint->status != 'ditolak')
                <div class="p-6 border-t border-gray-100 bg-white">
                    <form action="{{ route('admin.comments.store', $complaint->id) }}" method="POST" class="flex items-end gap-3">
                        @csrf
                        <div class="flex-1">
                            <textarea name="body" rows="2" placeholder="Tulis balasan untuk pelapor..." class="w-full border-gray-200 rounded-xl focus:border-bpjs-teal focus:ring focus:ring-bpjs-teal/20 text-sm resize-none" required></textarea>
                        </div>
                        <button type="submit" class="bg-bpjs-teal hover:bg-bpjs-dark text-white w-12 h-12 rounded-xl flex items-center justify-center shadow transition-colors flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
                @else
                <div class="p-4 bg-gray-100 text-center text-xs font-bold text-gray-500 uppercase">
                    Tiket sudah ditutup, diskusi tidak lagi tersedia.
                </div>
                @endif
            </div>

            @if($complaint->status == 'selesai' && !is_null($complaint->rating))
            <!-- Rating Result -->
            <div class="card-luxury mt-8 bg-white rounded-3xl shadow-xl border border-amber-200 p-6 md:p-8">
                <h3 class="text-xl font-extrabold text-bpjs-dark mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    Tingkat Kepuasan Pelapor
                </h3>
                <div class="flex items-center gap-1 mb-3">
                    @for($i=1; $i<=5; $i++)
                        <svg class="w-6 h-6 {{ $i <= $complaint->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endfor
                </div>
                @if($complaint->rating_review)
                <p class="text-sm text-gray-600 italic bg-amber-50/50 p-4 rounded-xl border border-amber-100">"{{ $complaint->rating_review }}"</p>
                @endif
            </div>
            @endif
        </div>

        <div class="lg:col-span-1">
            <div class="card-luxury sticky top-28 bg-bpjs-dark text-white border-t-4 border-bpjs-teal">
                <h4 class="text-xl font-black mb-2 tracking-tight">Tindak Lanjut</h4>
                <p class="text-xs text-gray-400 mb-8 leading-relaxed">Berikan respon resmi dan perbarui status untuk memberikan kepastian kepada pelapor.</p>

                <form action="{{ route('admin.pengaduan.update-status', $complaint->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-bpjs-teal mb-2">Pilih Status Baru</label>
                        <select name="status" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-bpjs-teal outline-none transition">
                            <option value="menunggu" {{ $complaint->status == 'menunggu' ? 'selected' : '' }}>Tetap Menunggu</option>
                            <option value="diproses" {{ $complaint->status == 'diproses' ? 'selected' : '' }}>Mulai Diproses</option>
                            <option value="selesai" {{ $complaint->status == 'selesai' ? 'selected' : '' }}>Selesaikan Laporan</option>
                            <option value="ditolak" {{ $complaint->status == 'ditolak' ? 'selected' : '' }}>Tolak Laporan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-bpjs-teal mb-2">Tanggapan Resmi</label>
                        <textarea name="catatan" rows="5" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-bpjs-teal outline-none transition" placeholder="Tuliskan alasan penolakan atau progres penanganan..."></textarea>
                    </div>

                    <button type="submit" class="w-full btn-primary-grad !rounded-xl !py-4 shadow-xl !from-bpjs-teal !to-bpjs-blue">
                        Update & Kirim Tanggapan
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection