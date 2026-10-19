<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('BPJS');
    }

    public function test_faq_public_page_displays_active_faqs(): void
    {
        Faq::create([
            'question' => 'Bagaimana cara berobat?',
            'answer' => 'Gunakan rujukan faskes 1.',
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Pertanyaan rahasia nonaktif',
            'answer' => 'Jawaban rahasia',
            'is_active' => false,
        ]);

        $response = $this->get('/faq');
        $response->assertStatus(200);
        $response->assertSee('Bagaimana cara berobat?');
        $response->assertDontSee('Pertanyaan rahasia nonaktif');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_pelapor_can_login_and_is_redirected_to_pengaduan(): void
    {
        $user = User::create([
            'name' => 'Budi Pelapor',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pelapor',
            'nik' => '3201234567890001',
            'nomor_bpjs' => '0001234567890',
        ]);

        $response = $this->post('/login', [
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/pengaduan');
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin BPJS',
            'email' => 'admin@bpjs.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@bpjs.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::create([
            'name' => 'User Test',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pelapor',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_new_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'nik' => '3201123456780002',
            'nomor_bpjs' => '0002123456789',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'siti@example.com',
            'nik' => '3201123456780002',
            'nomor_bpjs' => '0002123456789',
            'role' => 'pelapor',
        ]);

        $response->assertRedirect('/pengaduan');
        $this->assertAuthenticated();
    }

    public function test_registration_validation_fails_for_invalid_nik_or_bpjs(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Siti Salah',
            'email' => 'sitisalah@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'nik' => '123', // Bukan 16 digit
            'nomor_bpjs' => '456', // Bukan 13 digit
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['nik', 'nomor_bpjs']);
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@bpjs.test',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
