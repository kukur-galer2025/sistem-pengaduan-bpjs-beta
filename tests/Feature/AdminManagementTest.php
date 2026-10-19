<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pelapor;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Administrator BPJS',
            'email' => 'admin@bpjs.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->pelapor = User::create([
            'name' => 'Budi Santoso',
            'email' => 'pelapor@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pelapor',
            'nik' => '3201234567890001',
            'nomor_bpjs' => '0001234567890',
        ]);

        $this->category = Category::create([
            'nama_kategori' => 'Layanan Faskes 1',
            'slug' => 'layanan-faskes-1',
        ]);
    }

    public function test_pelapor_cannot_access_admin_dashboard_and_gets_403(): void
    {
        $response = $this->actingAs($this->pelapor)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_with_kpi(): void
    {
        Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-KPI1',
            'judul' => 'Antrean Berantakan',
            'kronologi' => 'Kronologi lengkap antrean.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Executive Dashboard');
        $response->assertSee('Antrean Berantakan');
    }

    public function test_admin_can_view_complaints_list_with_filter(): void
    {
        Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-W01',
            'judul' => 'Keluhan Menunggu',
            'kronologi' => 'Isi kronologi.',
            'status' => 'menunggu',
        ]);

        Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-D02',
            'judul' => 'Keluhan Diproses',
            'kronologi' => 'Isi kronologi.',
            'status' => 'diproses',
        ]);

        // Filter status menunggu
        $response = $this->actingAs($this->admin)->get('/admin/pengaduan?status=menunggu');
        $response->assertStatus(200);
        $response->assertSee('Keluhan Menunggu');
        $response->assertDontSee('Keluhan Diproses');
    }

    public function test_admin_can_view_complaint_detail(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-SHOW',
            'judul' => 'Pelayanan Poli Gigi Kurang',
            'kronologi' => 'Dokter tidak hadir.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/pengaduan/{$complaint->id}");
        $response->assertStatus(200);
        $response->assertSee('Pelayanan Poli Gigi Kurang');
        $response->assertSee('Tindak Lanjut');
    }

    public function test_admin_can_update_complaint_status_and_notifies_pelapor(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-NOTIF',
            'judul' => 'Kartu Rusak',
            'kronologi' => 'Kartu tidak terbaca.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/pengaduan/{$complaint->id}/status", [
            'status' => 'diproses',
            'catatan' => 'Laporan sedang ditindaklanjuti oleh petugas cabang.',
        ]);

        $response->assertRedirect("/admin/pengaduan/{$complaint->id}");
        $response->assertSessionHas('success');

        // Status di database terupdate
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'diproses',
        ]);

        // History tercatat dengan petugas_id milik admin
        $this->assertDatabaseHas('complaint_histories', [
            'complaint_id' => $complaint->id,
            'petugas_id' => $this->admin->id,
            'status' => 'diproses',
            'catatan' => 'Laporan sedang ditindaklanjuti oleh petugas cabang.',
        ]);

        // Notifikasi masuk ke akun pelapor
        $this->assertEquals(1, $this->pelapor->unreadNotifications()->count());
    }

    public function test_admin_can_export_complaints_pdf(): void
    {
        Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-PDF',
            'judul' => 'Keluhan Ekspor',
            'kronologi' => 'Isi kronologi ekspor.',
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/pengaduan/export-pdf');
        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_post_comment_on_complaint(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-ADMCOMM',
            'judul' => 'Klarifikasi Rumah Sakit',
            'kronologi' => 'Kronologi keluhan.',
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/pengaduan/{$complaint->id}/comments", [
            'body' => 'Mohon ditunggu, kami sedang memanggil direksi RS terkait.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('ticket_comments', [
            'complaint_id' => $complaint->id,
            'user_id' => $this->admin->id,
            'body' => 'Mohon ditunggu, kami sedang memanggil direksi RS terkait.',
        ]);
    }

    public function test_admin_can_manage_categories(): void
    {
        // 1. Tambah kategori
        $response = $this->actingAs($this->admin)->post('/admin/kategori', [
            'nama_kategori' => 'Kategori Baru BPJS',
        ]);
        $response->assertRedirect('/admin/kategori');
        $this->assertDatabaseHas('categories', [
            'nama_kategori' => 'Kategori Baru BPJS',
            'slug' => 'kategori-baru-bpjs',
        ]);

        $cat = Category::where('nama_kategori', 'Kategori Baru BPJS')->first();

        // 2. Update kategori
        $this->actingAs($this->admin)->put("/admin/kategori/{$cat->id}", [
            'nama_kategori' => 'Kategori Diperbarui',
        ]);
        $this->assertDatabaseHas('categories', [
            'id' => $cat->id,
            'nama_kategori' => 'Kategori Diperbarui',
            'slug' => 'kategori-diperbarui',
        ]);

        // 3. Hapus kategori tanpa pengaduan -> berhasil
        $this->actingAs($this->admin)->delete("/admin/kategori/{$cat->id}");
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);

        // 4. Proteksi: Hapus kategori yang memiliki pengaduan -> ditolak
        Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-PROT',
            'judul' => 'Pengaduan Kategori Aktif',
            'kronologi' => 'Kronologi.',
            'status' => 'menunggu',
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete("/admin/kategori/{$this->category->id}");
        $deleteResponse->assertSessionHasErrors('error');
        $this->assertDatabaseHas('categories', ['id' => $this->category->id]);
    }

    public function test_admin_can_manage_users_with_anti_self_deletion_and_demotion(): void
    {
        $targetUser = User::create([
            'name' => 'Calon Admin Baru',
            'email' => 'calon@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
            'nik' => '3201555555550005',
            'nomor_bpjs' => '0001555555555',
        ]);

        // 1. Ubah role user lain menjadi admin
        $this->actingAs($this->admin)->post("/admin/users/{$targetUser->id}/role", [
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'role' => 'admin',
        ]);

        // 2. Proteksi self-demotion: Admin tidak bisa mengubah role akunnya sendiri
        $selfRoleResponse = $this->actingAs($this->admin)->post("/admin/users/{$this->admin->id}/role", [
            'role' => 'pelapor',
        ]);
        $selfRoleResponse->assertSessionHasErrors();
        $this->assertEquals('admin', $this->admin->fresh()->role);

        // 3. Hapus user lain
        $this->actingAs($this->admin)->delete("/admin/users/{$targetUser->id}");
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);

        // 4. Proteksi self-deletion: Admin tidak bisa menghapus akunnya sendiri
        $selfDeleteResponse = $this->actingAs($this->admin)->delete("/admin/users/{$this->admin->id}");
        $selfDeleteResponse->assertSessionHasErrors();
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_can_manage_faqs(): void
    {
        // 1. Tambah FAQ
        $this->actingAs($this->admin)->post('/admin/faq', [
            'question' => 'Apakah iuran bisa dicicil?',
            'answer' => 'Program REHAB memungkinkan pembayaran bertahap.',
            'is_active' => '1',
        ]);

        $this->assertDatabaseHas('faqs', [
            'question' => 'Apakah iuran bisa dicicil?',
            'is_active' => true,
        ]);

        $faq = Faq::where('question', 'Apakah iuran bisa dicicil?')->first();

        // 2. Update FAQ
        $this->actingAs($this->admin)->put("/admin/faq/{$faq->id}", [
            'question' => 'Apakah iuran bisa dicicil? (Update)',
            'answer' => 'Bisa melalui program REHAB di Mobile JKN.',
        ]);
        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'question' => 'Apakah iuran bisa dicicil? (Update)',
            'is_active' => false,
        ]);

        // 3. Hapus FAQ
        $this->actingAs($this->admin)->delete("/admin/faq/{$faq->id}");
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }
}
