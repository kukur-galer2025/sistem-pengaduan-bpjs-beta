<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        // Menampilkan pengaduan milik user yang sedang login
        $complaints = Complaint::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('pelapor.pengaduan.index', compact('complaints'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('pelapor.pengaduan.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'judul' => 'required|string|max:255',
            'kronologi' => 'required|string',
            // Diperbarui: max 20480 setara dengan 20 MB
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480', 
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('lampiran_pengaduan', 'public');
        }

        // Generate Kode Tiket (Contoh: BPJS-20260418-XYZ1)
        $kodeTiket = 'BPJS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $complaint = Complaint::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'kode_tiket' => $kodeTiket,
            'judul' => $request->judul,
            'kronologi' => $request->kronologi,
            'lampiran_path' => $lampiranPath,
            'status' => 'menunggu',
        ]);

        // Simpan log awal di tabel histories
        ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'petugas_id' => null, // Belum ada petugas yang menangani
            'status' => 'menunggu',
            'catatan' => 'Pengaduan berhasil dibuat dan sedang menunggu verifikasi petugas.',
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim.');
    }

    public function show($id)
    {
        $complaint = Complaint::where('id', $id)
            ->where('user_id', Auth::id()) // Proteksi: pastikan ini milik pelapor
            ->with(['category', 'comments.user', 'histories' => function($query) {
                $query->latest(); // Urutkan history dari yang terbaru
            }])
            ->firstOrFail();

        return view('pelapor.pengaduan.show', compact('complaint'));
    }

    public function storeRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'rating_review' => 'nullable|string',
        ]);

        $complaint = Complaint::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->firstOrFail();

        $complaint->update([
            'rating' => $request->rating,
            'rating_review' => $request->rating_review,
        ]);

        return back()->with('success', 'Terima kasih atas penilaian Anda!');
    }
}