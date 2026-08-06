<?php

namespace App\Filament\Resources\LowonganPklResource\Pages;

use App\Filament\Resources\LowonganPklResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLowonganPkl extends EditRecord
{
    protected static string $resource = LowonganPklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
