<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'password' => Hash::make('old_password123'),
            'role' => 'pelapor',
            'nik' => '3201234567890001',
            'nomor_bpjs' => '0001234567890',
        ]);
    }

    public function test_guest_cannot_access_profile_edit_and_is_redirected_to_login(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_profile_edit_screen(): void
    {
        $response = $this->actingAs($this->user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('budi.santoso@example.com');
        $response->assertSeeText('Informasi Akun & Identitas');
    }

    public function test_user_can_update_profile_info(): void
    {
        $response = $this->actingAs($this->user)->put('/profile', [
            'name' => 'Budi Santoso Updated',
            'email' => 'budi.updated@example.com',
            'nik' => '3201234567890099',
            'nomor_bpjs' => '0001234567899',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Budi Santoso Updated',
            'email' => 'budi.updated@example.com',
            'nik' => '3201234567890099',
            'nomor_bpjs' => '0001234567899',
        ]);
    }

    public function test_profile_update_validation_fails_for_invalid_nik_or_bpjs(): void
    {
        $response = $this->actingAs($this->user)->put('/profile', [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'nik' => '999', // Kurang dari 16 digit
            'nomor_bpjs' => '888', // Kurang dari 13 digit
        ]);

        $response->assertSessionHasErrors(['nik', 'nomor_bpjs']);
    }

    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $response = $this->actingAs($this->user)->put('/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'nik' => $this->user->nik,
            'nomor_bpjs' => $this->user->nomor_bpjs,
            'current_password' => 'old_password123',
            'new_password' => 'new_secret_password',
            'new_password_confirmation' => 'new_secret_password',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('new_secret_password', $this->user->fresh()->password));
    }

    public function test_password_change_fails_if_current_password_is_incorrect(): void
    {
        $response = $this->actingAs($this->user)->put('/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'nik' => $this->user->nik,
            'nomor_bpjs' => $this->user->nomor_bpjs,
            'current_password' => 'wrong_current_password',
            'new_password' => 'new_secret_password',
            'new_password_confirmation' => 'new_secret_password',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('old_password123', $this->user->fresh()->password));
    }
}
