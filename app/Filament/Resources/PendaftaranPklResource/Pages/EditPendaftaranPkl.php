<?php

namespace App\Filament\Resources\PendaftaranPklResource\Pages;

use App\Filament\Resources\PendaftaranPklResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendaftaranPkl extends EditRecord
{
    protected static string $resource = PendaftaranPklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
