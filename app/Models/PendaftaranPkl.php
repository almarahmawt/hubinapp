<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPkl extends Model
{
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function lowonganPkl()
    {
        return $this->belongsTo(LowonganPkl::class);
    }
}