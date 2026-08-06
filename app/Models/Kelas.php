<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = [];

    public function kompetensi()
    {
        return $this->belongsTo(KompetensiKeahlian::class, 'kompetensi_id');
    }
}