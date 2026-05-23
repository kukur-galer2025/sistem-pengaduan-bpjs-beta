<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data rekapitulasi untuk widget dashboard
        $totalPengaduan = Complaint::count();
        $menunggu = Complaint::where('status', 'menunggu')->count();
        $diproses = Complaint::where('status', 'diproses')->count();
        $selesai = Complaint::where('status', 'selesai')->count();
        $ditolak = Complaint::where('status', 'ditolak')->count();

        // 5 Pengaduan terbaru
        $recentComplaints = Complaint::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPengaduan', 'menunggu', 'diproses', 'selesai', 'ditolak', 'recentComplaints'
        ));
    }
}