<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPkl extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom untuk disimpan
    protected $guarded = [];

    // Relasi yang dipanggil oleh Filament ->relationship('penempatanPkl', ...)
    public function penempatanPkl()
    {
        return $this->belongsTo(PenempatanPkl::class, 'penempatan_pkl_id');
    }
}