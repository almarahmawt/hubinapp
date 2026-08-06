<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LowonganPkl extends Model
{
    protected $guarded = [];

    public function industri()
    {
        return $this->belongsTo(Industri::class);
    }

    public function periode()
    {
        return $this->belongsTo(PeriodePkl::class);
    }
}