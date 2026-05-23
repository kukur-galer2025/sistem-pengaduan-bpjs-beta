<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Admin Utama
        User::create([
            'name' => 'Administrator BPJS',
            'email' => 'admin@bpjs.com',
            'password' => Hash::make('password'), // Password default: password
            'role' => 'admin',
            // NIK dan Nomor BPJS dibiarkan null karena ini Admin
        ]);

        // 2. Membuat Kategori Pengaduan Default
        $categories = [
            'Pelayanan Faskes Tingkat 1 (Klinik/Puskesmas)',
            'Pelayanan Rumah Sakit (Rujukan)',
            'Administrasi & Iuran',
            'Layanan Farmasi / Obat',
            'Lainnya'
        ];

        foreach ($categories as $category) {
            Category::create([
                'nama_kategori' => $category,
                'slug' => Str::slug($category),
            ]);
        }
    }
}