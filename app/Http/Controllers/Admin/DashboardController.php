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

        // Data Grafik: Tren Bulanan
        $monthExpr = \Illuminate\Support\Facades\DB::getDriverName() === 'sqlite'
            ? 'CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count'
            : 'MONTH(created_at) as month, COUNT(*) as count';

        $monthlyTrends = Complaint::selectRaw($monthExpr)
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')->toArray();

        $trendData = [];
        for ($i = 1; $i <= 12; $i++) {
            $trendData[] = $monthlyTrends[$i] ?? 0;
        }

        // Data Grafik: Distribusi Kategori
        $categoryDistribution = Complaint::join('categories', 'complaints.category_id', '=', 'categories.id')
            ->selectRaw('categories.nama_kategori as name, COUNT(complaints.id) as count')
            ->groupBy('categories.id', 'categories.nama_kategori')
            ->get();

        $categoryLabels = $categoryDistribution->pluck('name');
        $categoryData = $categoryDistribution->pluck('count');

        return view('admin.dashboard', compact(
            'totalPengaduan', 'menunggu', 'diproses', 'selesai', 'ditolak', 'recentComplaints',
            'trendData', 'categoryLabels', 'categoryData'
        ));
    }
}