@extends('layouts.admin')

@section('page_title', 'Kelola FAQ')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        <div class="card-luxury sticky top-28">
            <h4 class="text-lg font-bold text-bpjs-dark mb-4">Tambah FAQ Baru</h4>
            <form action="{{ route('admin.faq.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pertanyaan</label>
                    <input type="text" name="question" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-bpjs-blue outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jawaban</label>
                    <textarea name="answer" rows="5" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-bpjs-blue outline-none transition"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 text-bpjs-blue rounded border-gray-300 focus:ring-bpjs-blue">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Tampilkan di Halaman Publik</label>
                </div>
                <button type="submit" class="w-full btn-primary-grad !rounded-xl !py-3">
                    Simpan FAQ
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="card-luxury">
            <h4 class="text-lg font-bold text-bpjs-dark mb-6">Daftar FAQ</h4>
            
            @if(session('success'))
                <div class="bg-bpjs-teal text-white p-4 rounded-xl shadow-sm mb-6 flex items-center gap-3">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="space-y-4">
                @forelse($faqs as $faq)
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:border-bpjs-blue transition">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <h5 class="text-base font-bold text-bpjs-dark">{{ $faq->question }}</h5>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-md {{ $faq->is_active ? 'bg-bpjs-teal/10 text-bpjs-teal' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $faq->is_active ? 'Aktif' : 'Draft' }}
                                </span>
                                <!-- Delete Button -->
                                <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Hapus FAQ ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-gray-400 hover:text-red-500 transition"><i class="ph-bold ph-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">{{ $faq->answer }}</p>
                    </div>
                @empty
                    <div class="text-center py-10 bg-gray-50 rounded-2xl border border-gray-100 border-dashed">
                        <i class="ph-fill ph-question text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500 font-medium">Belum ada FAQ yang ditambahkan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $faqs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
