@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <div class="text-center mb-16" data-aos="fade-up">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-bpjs-blue/10 text-bpjs-blue mb-6">
            <i class="ph-fill ph-info text-3xl"></i>
        </div>
        <h1 class="text-4xl font-extrabold text-bpjs-dark mb-4">Pusat Bantuan & FAQ</h1>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto">Temukan jawaban untuk pertanyaan yang paling sering diajukan seputar layanan dan pelaporan BPJS.</p>
    </div>

    <div class="space-y-4" data-aos="fade-up" data-aos-delay="100">
        @forelse($faqs as $faq)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ expanded: false }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-6 text-left focus:outline-none hover:bg-gray-50 transition">
                <h3 class="text-lg font-bold text-bpjs-dark" :class="{ 'text-bpjs-blue': expanded }">{{ $faq->question }}</h3>
                <i class="ph-bold ph-caret-down text-gray-400 transition-transform duration-300" :class="{ 'rotate-180 text-bpjs-blue': expanded }"></i>
            </button>
            <div x-show="expanded" x-collapse>
                <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                    {{ $faq->answer }}
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-3xl shadow-sm border border-gray-100">
            <i class="ph-fill ph-ghost text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 font-medium">Belum ada informasi FAQ saat ini.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-16 text-center" data-aos="fade-up" data-aos-delay="200">
        <p class="text-gray-500 mb-4">Masih memiliki pertanyaan atau kendala?</p>
        <a href="{{ route('pengaduan.create') }}" class="btn-primary-grad">Buat Laporan Baru</a>
    </div>
</div>

<!-- Alpine.js for accordion -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
