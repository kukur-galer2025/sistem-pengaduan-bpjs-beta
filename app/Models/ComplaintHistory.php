<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintHistory extends Model
{
    /**
     * Daftarkan kolom-kolom yang diizinkan untuk diisi secara massal
     * (menggunakan fungsi create() atau update()).
     */
    protected $fillable = [
        'complaint_id', 
        'petugas_id', 
        'status', 
        'catatan'
    ];

    /**
     * Relasi: Riwayat ini merujuk pada satu pengaduan.
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * Relasi: Riwayat ini dieksekusi oleh satu petugas (admin).
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}