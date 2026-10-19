<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Menyimpan pembaruan data profil dan password.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Aturan validasi profil
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ];

        if ($user->role === 'pelapor') {
            $rules['nik'] = ['required', 'string', 'regex:/^\d{16}$/', 'unique:users,nik,' . $user->id];
            $rules['nomor_bpjs'] = ['required', 'string', 'regex:/^\d{13}$/', 'unique:users,nomor_bpjs,' . $user->id];
        } else {
            $rules['nik'] = ['nullable', 'string', 'regex:/^\d{16}$/', 'unique:users,nik,' . $user->id];
            $rules['nomor_bpjs'] = ['nullable', 'string', 'regex:/^\d{13}$/', 'unique:users,nomor_bpjs,' . $user->id];
        }

        // Jika user ingin mengganti password
        if ($request->filled('new_password')) {
            $rules['current_password'] = ['required'];
            $rules['new_password'] = ['required', 'min:8', 'confirmed'];
        }

        $messages = [
            'nik.regex' => 'NIK KTP harus terdiri dari tepat 16 digit angka.',
            'nomor_bpjs.regex' => 'Nomor Kartu BPJS harus terdiri dari tepat 13 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar oleh pengguna lain.',
            'nomor_bpjs.unique' => 'Nomor BPJS ini sudah terdaftar oleh pengguna lain.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'current_password.required' => 'Kata sandi saat ini wajib diisi jika ingin mengubah kata sandi.',
            'new_password.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ];

        $request->validate($rules, $messages);

        // Jika ganti password, verifikasi password saat ini
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan salah.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('nik')) {
            $user->nik = $request->nik;
        }
        if ($request->filled('nomor_bpjs')) {
            $user->nomor_bpjs = $request->nomor_bpjs;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
