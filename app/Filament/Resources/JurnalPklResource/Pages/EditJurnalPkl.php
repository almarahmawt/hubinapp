<?php

namespace App\Filament\Resources\JurnalPklResource\Pages;

use App\Filament\Resources\JurnalPklResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJurnalPkl extends EditRecord
{
    protected static string $resource = JurnalPklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
