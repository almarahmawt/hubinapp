<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JurnalPkl extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom untuk disimpan
    protected $guarded = [];

    protected $casts = [
        'budaya_kerja_5r' => 'array',
    ];

    // Relasi yang dipanggil oleh Filament ->relationship('penempatanPkl', ...)
    public function penempatanPkl()
    {
        return $this->belongsTo(PenempatanPkl::class, 'penempatan_pkl_id');
    }

    /**
     * deskripsi_kegiatan disimpan sebagai HTML dari RichEditor. strip_tags() saja
     * tidak cukup karena entity seperti &nbsp; tetap tertulis apa adanya (lalu
     * ter-escape ulang jadi "&amp;nbsp;" saat ditampilkan). Maka entity di-decode
     * dulu baru tag-nya dibuang, supaya hasilnya benar-benar teks polos.
     */
    public static function plainTextKegiatan(?string $html): string
    {
        $decoded = html_entity_decode($html ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $stripped = strip_tags($decoded);
        $noNbsp = str_replace("\xC2\xA0", ' ', $stripped);

        return (string) Str::of($noNbsp)->squish();
    }
}