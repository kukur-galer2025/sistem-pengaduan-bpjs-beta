<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- WAJIB ditambahkan untuk fungsi pembuatan slug otomatis

class CategoryController extends Controller
{
    // Menampilkan daftar kategori dan form tambah
    public function index()
    {
        $categories = Category::withCount('complaints')->latest()->get();
        return view('admin.kategori.index', compact('categories'));
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori'
        ], [
            'nama_kategori.unique' => 'Nama kategori ini sudah ada.'
        ]);

        Category::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori) // <-- Menyimpan slug ke database
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.kategori.edit', compact('category'));
    }

    // Menyimpan pembaruan kategori
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $category->id
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori) // <-- Memperbarui slug di database
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Menghapus kategori
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Proteksi: Jangan hapus jika ada pengaduan yang memakai kategori ini
        if ($category->complaints()->count() > 0) {
            return back()->withErrors(['error' => 'Kategori gagal dihapus karena sedang digunakan oleh ' . $category->complaints()->count() . ' data pengaduan.']);
        }

        $category->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}