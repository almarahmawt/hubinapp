<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industri extends Model
{
    use HasFactory;

    // Ini akan mengizinkan SEMUA kolom untuk diisi tanpa terkecuali
    protected $guarded = []; 
}