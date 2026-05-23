<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $users = User::when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%");
        })->latest()->paginate(15);

        return view('admin.users.index', compact('users', 'search'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:admin,pelapor']);
        $user = User::findOrFail($id);
        
        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return back()->withErrors('Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        $user->update(['role' => $request->role]);
        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->withErrors('Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
