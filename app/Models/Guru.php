<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guru extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor untuk menggabungkan gelar dan nama.
     * Penggunaan di Filament/Blade: $guru->nama_lengkap
     */
    public function getNamaLengkapAttribute(): string
    {
        $depan = $this->gelar_depan ? trim($this->gelar_depan) . ' ' : '';
        // Memastikan ada koma spasi sebelum gelar belakang
        $belakang = $this->gelar_belakang
            ? ', ' . trim($this->gelar_belakang)
            : '';

        return "{$depan}{$this->nama}{$belakang}";
    }

    public function penempatanPkls()
    {
        return $this->hasMany(PenempatanPkl::class, 'guru_id');
    }

    public function jurnalPkls()
    {
        return $this->hasManyThrough(
            JurnalPkl::class, // model tujuan
            PenempatanPkl::class, // model perantara
            'guru_id', // FK di penempatan_pkls
            'penempatan_pkl_id', // FK di jurnal_pkls
        );
    }

    // User.php (kalau gurus punya kolom user_id)
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }
}
