@extends('layouts.admin')

@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="card-luxury">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h4 class="text-xl font-bold text-bpjs-dark">Daftar Pengguna</h4>
            <p class="text-sm text-gray-400">Kelola akun admin dan pelapor yang terdaftar di sistem.</p>
        </div>
        
        <form action="{{ route('admin.users.index') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari NIK / Nama / Email..." class="bg-gray-100 border border-gray-200 rounded-full pl-10 pr-4 py-2 w-full md:w-64 text-sm focus:ring-2 focus:ring-bpjs-blue outline-none transition">
            <i class="ph-bold ph-magnifying-glass text-gray-400 absolute left-4 top-2.5"></i>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-bpjs-teal text-white p-4 rounded-xl shadow-sm mb-6 flex items-center gap-3">
            <i class="ph-fill ph-check-circle text-xl"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-xl shadow-sm mb-6 flex items-center gap-3">
            <i class="ph-fill ph-warning-circle text-xl"></i>
            <span class="font-bold">{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <th class="pb-4 px-2">Pengguna</th>
                    <th class="pb-4 px-2">NIK / No. BPJS</th>
                    <th class="pb-4 px-2">Role</th>
                    <th class="pb-4 px-2">Terdaftar</th>
                    <th class="pb-4 px-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                <tr class="group hover:bg-gray-50/50 transition">
                    <td class="py-5 px-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm
                                {{ $user->role == 'admin' ? 'bg-bpjs-dark' : 'bg-bpjs-blue' }}">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-bpjs-dark">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-5 px-2">
                        <div class="text-sm font-medium text-gray-700">{{ $user->nik ?? '-' }}</div>
                        <div class="text-xs text-gray-400">{{ $user->nomor_bpjs ?? '-' }}</div>
                    </td>
                    <td class="py-5 px-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                            {{ $user->role == 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-blue-50 text-bpjs-blue' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="py-5 px-2 text-sm text-gray-500 font-medium">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="py-5 px-2 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Ubah Role Form -->
                            <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="role" value="{{ $user->role == 'admin' ? 'pelapor' : 'admin' }}">
                                <button type="submit" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-bpjs-blue hover:text-white transition tooltip-btn" title="Ubah Role">
                                    <i class="ph-bold ph-arrows-left-right"></i>
                                </button>
                            </form>
                            
                            <!-- Hapus Form -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition tooltip-btn" title="Hapus Pengguna">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
