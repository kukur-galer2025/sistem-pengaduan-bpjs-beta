<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\TicketComment;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    public function store(Request $request, $complaint_id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $complaint = Complaint::findOrFail($complaint_id);
        $user = auth()->user();

        // Cek otorisasi: hanya admin atau pelapor pemilik tiket yang berhak komentar
        if ($user->role !== 'admin' && $complaint->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk berkomentar pada tiket ini.');
        }

        // Tiket yang sudah ditutup tidak boleh dikomentari
        if (in_array($complaint->status, ['selesai', 'ditolak'])) {
            return back()->withErrors(['body' => 'Tiket sudah ditutup, diskusi tidak lagi dapat ditambahkan.']);
        }

        TicketComment::create([
            'complaint_id' => $complaint->id,
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }
}
