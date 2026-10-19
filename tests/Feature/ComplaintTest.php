<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ComplaintTest extends TestCase
{
    use RefreshDatabase;

    private User $pelapor;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pelapor = User::create([
            'name' => 'Budi Pelapor',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pelapor',
            'nik' => '3201234567890001',
            'nomor_bpjs' => '0001234567890',
        ]);

        $this->category = Category::create([
            'nama_kategori' => 'Pelayanan RS',
            'slug' => 'pelayanan-rs',
        ]);
    }

    public function test_guest_cannot_access_pengaduan_and_is_redirected_to_login(): void
    {
        $response = $this->get('/pengaduan');
        $response->assertRedirect('/login');
    }

    public function test_pelapor_can_view_own_complaints_list(): void
    {
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
            'nik' => '3201999999990002',
            'nomor_bpjs' => '0001999999999',
        ]);

        $myComplaint = Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-MY01',
            'judul' => 'Keluhan Milik Budi',
            'kronologi' => 'Antrean sangat panjang di poli bedah.',
            'status' => 'menunggu',
        ]);

        $otherComplaint = Complaint::create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-OT02',
            'judul' => 'Keluhan Orang Lain',
            'kronologi' => 'Kamar rawat inap penuh.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->pelapor)->get('/pengaduan');

        $response->assertStatus(200);
        $response->assertSee('Keluhan Milik Budi');
        $response->assertDontSee('Keluhan Orang Lain');
    }

    public function test_pelapor_can_access_create_complaint_form(): void
    {
        $response = $this->actingAs($this->pelapor)->get('/pengaduan/create');
        $response->assertStatus(200);
        $response->assertSee('Pelayanan RS');
    }

    public function test_pelapor_can_store_new_complaint(): void
    {
        $response = $this->actingAs($this->pelapor)->post('/pengaduan', [
            'category_id' => $this->category->id,
            'judul' => 'Pelayanan Farmasi Kurang Ramah',
            'kronologi' => 'Petugas di loket obat bersikap ketus saat dimintai informasi dosis.',
        ]);

        $response->assertRedirect('/pengaduan');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'judul' => 'Pelayanan Farmasi Kurang Ramah',
            'status' => 'menunggu',
        ]);

        $complaint = Complaint::where('judul', 'Pelayanan Farmasi Kurang Ramah')->first();
        $this->assertNotNull($complaint);
        $this->assertStringStartsWith('BPJS-', $complaint->kode_tiket);

        // Pastikan riwayat awal tercatat otomatis
        $this->assertDatabaseHas('complaint_histories', [
            'complaint_id' => $complaint->id,
            'status' => 'menunggu',
        ]);
    }

    public function test_pelapor_can_store_complaint_with_file_attachments(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('bukti_foto.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($this->pelapor)->post('/pengaduan', [
            'category_id' => $this->category->id,
            'judul' => 'Kwitansi Bermasalah',
            'kronologi' => 'Biaya obat ditagihkan di luar tanggungan.',
            'lampiran' => [$file],
        ]);

        $response->assertRedirect('/pengaduan');

        $complaint = Complaint::where('judul', 'Kwitansi Bermasalah')->first();
        $this->assertNotNull($complaint);

        $attachment = $complaint->attachments()->first();
        $this->assertNotNull($attachment);
        $this->assertEquals('bukti_foto.jpg', $attachment->file_name);
        Storage::disk('public')->assertExists($attachment->file_path);
    }

    public function test_pelapor_can_view_own_complaint_detail(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-VIEW',
            'judul' => 'Rujukan Ditolak',
            'kronologi' => 'Puskesmas menolak memberikan rujukan lanjutan.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->pelapor)->get("/pengaduan/{$complaint->id}");

        $response->assertStatus(200);
        $response->assertSee('Rujukan Ditolak');
        $response->assertSee('BPJS-20261019-VIEW');
    }

    public function test_pelapor_cannot_view_other_users_complaint(): void
    {
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other2@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
            'nik' => '3201888888880003',
            'nomor_bpjs' => '0001888888888',
        ]);

        $otherComplaint = Complaint::create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-PRIV',
            'judul' => 'Rahasia Pelapor Lain',
            'kronologi' => 'Kronologi rahasia.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->pelapor)->get("/pengaduan/{$otherComplaint->id}");
        $response->assertStatus(404);
    }

    public function test_pelapor_can_post_comment_on_their_complaint(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-COMM',
            'judul' => 'Klarifikasi Obat',
            'kronologi' => 'Klarifikasi mengenai ketersediaan obat.',
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($this->pelapor)->post("/pengaduan/{$complaint->id}/comments", [
            'body' => 'Saya sudah menghubungi pihak apotek pagi ini.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('ticket_comments', [
            'complaint_id' => $complaint->id,
            'user_id' => $this->pelapor->id,
            'body' => 'Saya sudah menghubungi pihak apotek pagi ini.',
        ]);
    }

    public function test_pelapor_cannot_post_comment_on_another_users_complaint(): void
    {
        $otherUser = User::create([
            'name' => 'Korban IDOR',
            'email' => 'korban@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
            'nik' => '3201777777770004',
            'nomor_bpjs' => '0001777777777',
        ]);

        $otherComplaint = Complaint::create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-IDOR',
            'judul' => 'Tiket Korban',
            'kronologi' => 'Isi laporan.',
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($this->pelapor)->post("/pengaduan/{$otherComplaint->id}/comments", [
            'body' => 'Komentar spam tidak berizin.',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('ticket_comments', [
            'body' => 'Komentar spam tidak berizin.',
        ]);
    }

    public function test_pelapor_can_submit_rating_when_complaint_status_is_selesai(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-RATE',
            'judul' => 'Keluhan Selesai',
            'kronologi' => 'Sudah tuntas.',
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($this->pelapor)->post("/pengaduan/{$complaint->id}/rating", [
            'rating' => 5,
            'rating_review' => 'Pelayanan sangat memuaskan dan cepat tanggap.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'rating' => 5,
            'rating_review' => 'Pelayanan sangat memuaskan dan cepat tanggap.',
        ]);
    }

    public function test_pelapor_cannot_submit_rating_when_status_is_not_selesai(): void
    {
        $complaint = Complaint::create([
            'user_id' => $this->pelapor->id,
            'category_id' => $this->category->id,
            'kode_tiket' => 'BPJS-20261019-NOTR',
            'judul' => 'Keluhan Masih Diproses',
            'kronologi' => 'Sedang dicek.',
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($this->pelapor)->post("/pengaduan/{$complaint->id}/rating", [
            'rating' => 5,
        ]);

        $response->assertStatus(404);
    }
}
