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
        $admin = User::firstOrCreate(['email' => 'admin@bpjs.com'], [
            'name' => 'Administrator BPJS',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Membuat Akun Pelapor
        $pelapor = User::firstOrCreate(['email' => 'pelapor@gmail.com'], [
            'name' => 'Budi Santoso',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
            'nik' => '3201234567890001',
            'nomor_bpjs' => '0001234567890'
        ]);

        // 3. Membuat Kategori Pengaduan Default
        $categories = [
            'Pelayanan Faskes Tingkat 1 (Klinik/Puskesmas)',
            'Pelayanan Rumah Sakit (Rujukan)',
            'Administrasi & Iuran',
            'Layanan Farmasi / Obat',
            'Lainnya'
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['nama_kategori' => $category], [
                'slug' => Str::slug($category),
            ]);
        }

        // 4. Membuat Sample FAQ
        \App\Models\Faq::firstOrCreate(['question' => 'Bagaimana cara menggunakan Mobile JKN?'], [
            'answer' => 'Anda bisa mengunduh aplikasi Mobile JKN di PlayStore atau AppStore. Silakan login menggunakan NIK atau Nomor Kartu BPJS Anda beserta password yang telah didaftarkan.',
            'is_active' => true
        ]);
        
        \App\Models\Faq::firstOrCreate(['question' => 'Berapa lama proses penanganan keluhan?'], [
            'answer' => 'Proses penanganan keluhan umumnya memakan waktu 1x24 jam hingga maksimal 3x24 jam hari kerja, tergantung pada tingkat kompleksitas masalah.',
            'is_active' => true
        ]);

        // 5. Membuat Sample Pengaduan
        $kategori1 = Category::where('nama_kategori', 'Pelayanan Faskes Tingkat 1 (Klinik/Puskesmas)')->first();
        $kategori2 = Category::where('nama_kategori', 'Administrasi & Iuran')->first();

        // Tiket 1: Status Selesai
        $complaint1 = \App\Models\Complaint::firstOrCreate(['kode_tiket' => 'BPJS-20260523-ABCD'], [
            'user_id' => $pelapor->id,
            'category_id' => $kategori1->id,
            'judul' => 'Antrean di Klinik Sehat Terlalu Lama',
            'kronologi' => 'Saya datang jam 7 pagi namun baru dilayani jam 1 siang padahal antrean tidak terlalu banyak.',
            'status' => 'selesai',
            'rating' => 4,
            'rating_review' => 'Terima kasih atas tanggapan cepatnya. Semoga pelayanan klinik bisa ditingkatkan.'
        ]);

        if ($complaint1->wasRecentlyCreated) {
            \App\Models\ComplaintHistory::create([
                'complaint_id' => $complaint1->id,
                'petugas_id' => null,
                'status' => 'menunggu',
                'catatan' => 'Pengaduan berhasil dibuat dan sedang menunggu verifikasi petugas.',
            ]);

            \App\Models\ComplaintHistory::create([
                'complaint_id' => $complaint1->id,
                'petugas_id' => $admin->id,
                'status' => 'diproses',
                'catatan' => 'Laporan sedang kami verifikasi ke pihak Klinik Sehat.',
            ]);

            \App\Models\ComplaintHistory::create([
                'complaint_id' => $complaint1->id,
                'petugas_id' => $admin->id,
                'status' => 'selesai',
                'catatan' => 'Pihak klinik telah diberikan teguran dan akan melakukan evaluasi sistem antrean. Laporan ditutup.',
            ]);
        }

        // Tiket 2: Status Diproses
        $complaint2 = \App\Models\Complaint::firstOrCreate(['kode_tiket' => 'BPJS-20260523-XYZ1'], [
            'user_id' => $pelapor->id,
            'category_id' => $kategori2->id,
            'judul' => 'Status Kepesertaan Non-Aktif',
            'kronologi' => 'Saya sudah membayar iuran bulan ini, namun status kepesertaan di Mobile JKN masih non-aktif. Mohon bantuannya.',
            'status' => 'diproses',
        ]);

        if ($complaint2->wasRecentlyCreated) {
            \App\Models\ComplaintHistory::create([
                'complaint_id' => $complaint2->id,
                'petugas_id' => null,
                'status' => 'menunggu',
                'catatan' => 'Pengaduan berhasil dibuat dan sedang menunggu verifikasi petugas.',
            ]);

            \App\Models\ComplaintHistory::create([
                'complaint_id' => $complaint2->id,
                'petugas_id' => $admin->id,
                'status' => 'diproses',
                'catatan' => 'Kami sedang melakukan pengecekan mutasi pembayaran pada sistem. Mohon menunggu.',
            ]);

            // Sample Komentar Diskusi Tiket 2
            \App\Models\TicketComment::create([
                'complaint_id' => $complaint2->id,
                'user_id' => $admin->id,
                'body' => 'Halo Bapak Budi, bisakah Anda melampirkan bukti transfer pembayaran bulan ini?'
            ]);
            
            \App\Models\TicketComment::create([
                'complaint_id' => $complaint2->id,
                'user_id' => $pelapor->id,
                'body' => 'Baik, tunggu sebentar saya carikan buktinya ya pak.'
            ]);
        }
    }
}