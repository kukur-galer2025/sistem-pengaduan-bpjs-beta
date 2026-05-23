<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'nama_kategori',
        'slug' // <-- Ditambahkan agar bisa diisi secara massal
    ];

    /**
     * Relasi: Satu kategori bisa memiliki banyak pengaduan
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}