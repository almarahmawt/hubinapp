<?php

namespace App\Filament\Resources\PendaftaranPklResource\Pages;

use App\Filament\Resources\PendaftaranPklResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePendaftaranPkl extends CreateRecord
{
    protected static string $resource = PendaftaranPklResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('Siswa')) {
            
            // REM DARURAT: Jika akun belum dihubungkan dengan biodata Siswa oleh Admin
            if (auth()->user()->siswa === null) {
                Notification::make()
                    ->danger()
                    ->title('Akses Ditolak')
                    ->body('Akun Anda belum dihubungkan dengan biodata Siswa. Silakan hubungi Staf PKL/Admin.')
                    ->send();
                
                $this->halt(); // Membatalkan proses penyimpanan ke database seketika!
            }

            // Jika aman, lanjutkan penimpaan data
            $data['siswa_id'] = auth()->user()->siswa->id;
            $data['status'] = 'Menunggu';
        }

        return $data;
    }
}
