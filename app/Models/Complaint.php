<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    /**
     * Mendefinisikan kolom yang boleh diisi (Mass Assignment).
     * Menggantikan #[Fillable] agar lebih kompatibel dan mencegah error.
     */
    protected $fillable = [
        'user_id', 
        'category_id', 
        'kode_tiket', 
        'judul', 
        'kronologi', 
        'lampiran_path', 
        'status',
        'rating',
        'rating_review'
    ];

    /**
     * Relasi: Pengaduan ini milik satu user (pelapor).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Pengaduan ini masuk ke dalam satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Satu pengaduan memiliki banyak riwayat pelacakan status.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(ComplaintHistory::class);
    }

    /**
     * Relasi: Satu pengaduan memiliki banyak komentar diskusi.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }
}