<?php

namespace Database\Seeders;

use App\Models\KompetensiKeahlian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------
        // 1. BUAT ROLE (HAK AKSES)
        // ---------------------------------------------------
        // Catatan: Filament Shield biasanya menggunakan 'super_admin' sebagai nama role tertingginya
        $roleAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Staf PKL', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Siswa', 'guard_name' => 'web']);

        // ---------------------------------------------------
        // 2. BUAT AKUN SUPER ADMIN PERTAMA
        // ---------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_DEFAULT_EMAIL', 'admin@local.test')], // Ambil dari .env
            [
                'name' => 'Administrator Hubin',
                'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'password')), // Ambil dari .env
            ]
        );
        $admin->assignRole($roleAdmin);

        // ---------------------------------------------------
        // 3. DATA KOMPETENSI KEAHLIAN (JURUSAN)
        // ---------------------------------------------------
        $jurusans = [
            ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak'],
            ['kode' => 'TKJ', 'nama' => 'Teknik Komputer dan Jaringan'],
            ['kode' => 'DKV', 'nama' => 'Desain Komunikasi Visual'],
            ['kode' => 'TOI', 'nama' => 'Teknik Otomasi Industri'],
        ];

        foreach ($jurusans as $jurusan) {
            // Gunakan 'kode' sebagai acuan untuk mencari/membuat data
            KompetensiKeahlian::firstOrCreate(
                ['kode' => $jurusan['kode']], 
                ['nama' => $jurusan['nama']]
            );
        }

        $this->command->info('Master Data berhasil di-seed! (Roles, Admin, dan Kompetensi Keahlian sudah siap).');
    }
}