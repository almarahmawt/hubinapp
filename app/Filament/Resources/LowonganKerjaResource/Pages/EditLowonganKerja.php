<?php

namespace App\Filament\Resources\LowonganKerjaResource\Pages;

use App\Filament\Resources\LowonganKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLowonganKerja extends EditRecord
{
    protected static string $resource = LowonganKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
