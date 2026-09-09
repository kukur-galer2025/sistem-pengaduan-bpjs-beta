<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\ComplaintStatusUpdated;

class ComplaintManagementController extends Controller
{
    public function index(Request $request)
    {
        // Filter parameters
        $status = $request->query('status');
        $category_id = $request->query('category_id');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        $complaints = Complaint::with(['user', 'category'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($category_id, function ($query, $category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($start_date, function ($query, $start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query, $end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = \App\Models\Category::all();

        return view('admin.pengaduan.index', compact('complaints', 'categories'));
    }

    public function show($id)
    {
        $complaint = Complaint::with(['user', 'category', 'histories.petugas', 'comments.user'])
            ->findOrFail($id);

        return view('admin.pengaduan.show', compact('complaint'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,ditolak',
            'catatan' => 'required|string',
        ]);

        $complaint = Complaint::findOrFail($id);

        // Update status di tabel utama
        $complaint->update([
            'status' => $request->status,
        ]);

        // Catat di tabel histories & simpan ke variabel $history
        $history = ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'petugas_id' => Auth::id(), // Admin yang sedang login
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        // Kirim notifikasi ke pelapor
        $complaint->user->notify(new ComplaintStatusUpdated($complaint));

        return redirect()->route('admin.pengaduan.show', $id)
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    public function exportPdf(Request $request)
    {
        // Ambil bulan dan tahun dari request, atau gunakan bulan ini jika kosong
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        // Ambil data pengaduan berdasarkan bulan dan tahun
        $complaints = Complaint::with(['user', 'category'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();

        // Load view khusus PDF dan passing datanya
        $pdf = Pdf::loadView('admin.pengaduan.pdf', compact('complaints', 'month', 'year'));

        // Mengatur ukuran kertas ke A4 Landscape
        $pdf->setPaper('a4', 'landscape');

        // Nama file saat di-download
        $fileName = 'Laporan_Pengaduan_BPJS_' . date('F', mktime(0, 0, 0, $month, 1)) . '_' . $year . '.pdf';

        return $pdf->download($fileName);
    }
}