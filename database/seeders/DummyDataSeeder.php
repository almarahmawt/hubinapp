<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Industri;
use App\Models\KompetensiKeahlian; 
use App\Models\LowonganPkl;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\PeriodePkl;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil Data Kompetensi Keahlian
        $rpl = KompetensiKeahlian::first();
        $tkj = KompetensiKeahlian::skip(1)->first();

        if (!$tkj) {
            $tkj = $rpl;
        }

        if (!$rpl) {
            $this->command->error('Tabel Kompetensi Keahlian masih KOSONG!');
            return;
        }

        // 2. Buat Akun & Data Guru Pembimbing
        $userGuru = User::firstOrCreate(
            ['email' => 'guru@sekolah.com'],
            ['name' => 'Pak Budi Guru', 'password' => Hash::make('password')]
        );
        $userGuru->assignRole('Guru');

        Guru::firstOrCreate(
            ['nip' => '198001012005011001'],
            ['nama' => 'Pak Budi Guru', 'no_hp' => '081234567890']
        );

        // 3. Membuat kelas Dummy (Tingkat sudah ditambahkan)
        $kelasDummy = Kelas::firstOrCreate(
            [
                'nama' => 'XI RPL 1',
                'kompetensi_id' => $rpl->id,
                'tingkat' => 11
            ]
        );

        // 4. Buat Akun & Data Siswa Tester (Budi Siswa)
        $userSiswa = User::firstOrCreate(
            ['email' => 'budi@sekolah.com'],
            ['name' => 'Budi Siswa', 'password' => Hash::make('password')]
        );
        $userSiswa->assignRole('Siswa');

        Siswa::firstOrCreate(
            ['nisn' => '0011223344'],
            [
                'nis' => '24251001', // <--- TAMBAHKAN NIS UNTUK BUDI
                'nama' => 'Budi Siswa',
                'kompetensi_id' => $rpl->id,
                'kelas_id' => $kelasDummy->id,
                'no_hp' => '089876543210',
                'user_id' => $userSiswa->id
            ]
        );

        // 5. Buat Akun & Data Siswa Rival (Andi Rival)
        $userSiswa2 = User::firstOrCreate(
            ['email' => 'andi@sekolah.com'],
            ['name' => 'Andi Rival', 'password' => Hash::make('password')]
        );
        $userSiswa2->assignRole('Siswa');

        Siswa::firstOrCreate(
            ['nisn' => '0055667788'],
            [
                'nis' => '24251002', // <--- TAMBAHKAN NIS UNTUK ANDI
                'nama' => 'Andi Rival',
                'kompetensi_id' => $rpl->id,
                'kelas_id' => $kelasDummy->id,
                'no_hp' => '088888888888',
                'user_id' => $userSiswa2->id
            ]
        );

        // 6. Buat Data Industri
        $telkom = Industri::firstOrCreate(
            ['nama' => 'PT Telkom Indonesia'],
            [
                'alamat' => 'Jl. Japati No. 1, Bandung',
                'kompetensi_id' => $tkj->id // <--- TAMBAHKAN JURUSAN TKJ DI SINI
            ]
        );

        $len = Industri::firstOrCreate(
            ['nama' => 'PT Len Industri'],
            [
                'alamat' => 'Jl. Soekarno Hatta No. 442, Bandung',
                'kompetensi_id' => $rpl->id // <--- TAMBAHKAN JURUSAN RPL DI SINI
            ]
        );

        // 6.5 Buat Data Periode PKL (Karena sebelumnya kosong, kita buatkan yang baru)
        $periode = PeriodePkl::firstOrCreate(
            ['nama' => 'Gelombang 1'], // Cek apakah Gelombang 1 sudah ada
            [
                'tahun_ajaran' => '2026/2027',
                'tanggal_mulai' => '2026-07-01',
                'tanggal_selesai' => '2026-12-31'
            ]
        );

        // 7. Buat Etalase Lowongan PKL
        LowonganPkl::firstOrCreate(
            ['industri_id' => $telkom->id, 'periode_id' => $periode->id],
            ['kuota' => 2, 'syarat_khusus' => 'Menguasai jaringan dasar dan bersedia shift malam.']
        );

        LowonganPkl::firstOrCreate(
            ['industri_id' => $len->id, 'periode_id' => $periode->id],
            ['kuota' => 1, 'syarat_khusus' => 'Memahami dasar-dasar Laravel dan Vue.js.']
        );

        $this->command->info('Data Dummy berhasil di-seed! Budi, Andi, dan Lowongan PKL siap diuji.');
    }
}