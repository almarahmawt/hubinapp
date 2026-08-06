<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganKerja extends Model
{
    use HasFactory;

    // Buka gembok agar semua kolom bisa diisi
    protected $guarded = [];

    // Definisikan relasinya (1 Lowongan Kerja adalah milik 1 Industri)
    public function industri()
    {
        return $this->belongsTo(Industri::class);
    }
}