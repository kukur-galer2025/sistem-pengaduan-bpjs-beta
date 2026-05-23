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

        TicketComment::create([
            'complaint_id' => $complaint->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }
}
