@extends('layouts.admin')

@section('page_title', 'Edit Kategori Pengaduan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-luxury p-6 md:p-8 bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 text-lg">Perbarui Kategori</h3>
            <a href="{{ route('admin.kategori.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600">← Kembali</a>
        </div>
        
        <form action="{{ route('admin.kategori.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nama Kategori</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-bpjs-blue focus:border-bpjs-blue transition">
                @error('nama_kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="btn-primary-grad px-6 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection