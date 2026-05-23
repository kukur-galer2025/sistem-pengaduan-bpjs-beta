@extends('layouts.admin')

@section('page_title', 'Kelola Kategori Pengaduan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <div class="md:col-span-1">
        <div class="card-luxury p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Tambah Kategori Baru</h3>
            
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-bpjs-blue focus:border-bpjs-blue transition" placeholder="Misal: Layanan Faskes Pertama">
                    @error('nama_kategori')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full btn-primary-grad px-4 py-2 rounded-lg text-sm font-bold text-white shadow-md">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="card-luxury bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 text-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @error('error')
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 text-sm" role="alert">
                    {{ $message }}
                </div>
            @enderror

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50">
                            <th class="py-4 px-6">Nama Kategori</th>
                            <th class="py-4 px-6 text-center">Jumlah Pengaduan</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6 font-bold text-gray-700">{{ $category->nama_kategori }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $category->complaints_count }} Tiket
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right flex justify-end gap-2">
                                <a href="{{ route('admin.kategori.edit', $category->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md text-xs font-bold transition">Edit</a>
                                
                                <form action="{{ route('admin.kategori.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-bold transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-400 text-sm">Belum ada kategori yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection