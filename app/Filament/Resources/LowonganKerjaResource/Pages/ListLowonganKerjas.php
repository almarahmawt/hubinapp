<?php

namespace App\Filament\Resources\LowonganKerjaResource\Pages;

use App\Filament\Resources\LowonganKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLowonganKerjas extends ListRecords
{
    protected static string $resource = LowonganKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
