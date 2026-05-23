<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Jika validasi kosong, otomatis memicu pop-up error dari layout
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role beserta pesan sukses
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('admin/dashboard')
                    ->with('success', 'Selamat datang kembali, Admin!');
            }

            return redirect()->intended('pengaduan')
                ->with('success', 'Login berhasil! Selamat datang di BPJS Care Portal.');
        }

        // Jika email/password salah, kembalikan dengan pesan error (memicu pop-up error)
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Pesan error kustom agar lebih informatif saat pop-up error muncul
        $messages = [
            'nik.regex' => 'NIK KTP harus terdiri dari tepat 16 digit angka tanpa karakter lain.',
            'nomor_bpjs.regex' => 'Nomor Kartu BPJS harus terdiri dari tepat 13 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'nomor_bpjs.unique' => 'Nomor BPJS ini sudah terdaftar di sistem.',
        ];

        // Validasi ini otomatis akan memicu popup error jika gagal
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
            'nik' => ['required', 'string', 'regex:/^\d{16}$/', 'unique:users'],
            'nomor_bpjs' => ['required', 'string', 'regex:/^\d{13}$/', 'unique:users'],
        ], $messages);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'nomor_bpjs' => $request->nomor_bpjs,
            'role' => 'pelapor', // Default role untuk registrasi publik
        ]);

        Auth::login($user);

        // Redirect dengan membawa pesan sukses pendaftaran
        return redirect('pengaduan')
            ->with('success', 'Pendaftaran berhasil! Akun Anda telah aktif.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman depan dengan pesan sukses keluar
        return redirect('/')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}