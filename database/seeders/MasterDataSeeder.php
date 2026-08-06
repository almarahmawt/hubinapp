<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KompetensiKeahlian;
use Spatie\Permission\Models\Role;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Mengisi Data Jurusan (Kompetensi Keahlian)
        $jurusans = [
            ['nama' => 'Kimia Analisis', 'kode' => 'KA'],
            ['nama' => 'Teknik Komputer dan Jaringan', 'kode' => 'TKJ'],
            ['nama' => 'Rekayasa Perangkat Lunak', 'kode' => 'RPL'],
        ];

        foreach ($jurusans as $jurusan) {
            KompetensiKeahlian::firstOrCreate($jurusan);
        }

        // 2. Membuat Role Pengguna
        $roles = [
            'Admin',
            'Staf PKL',
            'Guru Pembimbing',
            'Siswa'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}