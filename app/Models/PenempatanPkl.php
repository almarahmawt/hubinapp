<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenempatanPkl extends Model
{
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function industri()
    {
        return $this->belongsTo(Industri::class, 'industri_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function jurnalPkls()
    {
        return $this->hasMany(JurnalPkl::class);
    }
}
