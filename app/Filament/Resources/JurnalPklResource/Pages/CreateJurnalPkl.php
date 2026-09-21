<?php

namespace App\Filament\Resources\JurnalPklResource\Pages;

use App\Filament\Resources\JurnalPklResource;
use App\Models\Siswa;
use App\Models\PenempatanPkl;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateJurnalPkl extends CreateRecord
{
    protected static string $resource = JurnalPklResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userId = auth()->id();

        // 1. Cek apakah user yang login terdaftar di tabel Siswa
        $siswa = Siswa::where('user_id', $userId)->first();

        if ($siswa) {
            // 2. Ambil penempatan PKL milik siswa tersebut
            $penempatan = PenempatanPkl::where('siswa_id', $siswa->id)->latest()->first();

            if (! $penempatan) {
                throw ValidationException::withMessages([
                    'status_kehadiran' => 'Gagal menyimpan: Anda belum terdaftar di tempat PKL manapun. Silakan hubungi Admin!',
                ]);
            }

            // 3. Inject ID penempatan ke dalam data sebelum di-insert ke database
            $data['penempatan_pkl_id'] = $penempatan->id;
        } else {
            // Jika login sebagai Admin/Super Admin, pastikan dropdown penempatan terisi
            if (empty($data['penempatan_pkl_id'])) {
                throw ValidationException::withMessages([
                    'penempatan_pkl_id' => 'Silakan pilih Siswa / Penempatan PKL terlebih dahulu.',
                ]);
            }
        }

        return $data;
    }
}