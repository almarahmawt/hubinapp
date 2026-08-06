<?php

namespace App\Filament\Resources\LowonganPklResource\Pages;

use App\Filament\Resources\LowonganPklResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLowonganPkls extends ListRecords
{
    protected static string $resource = LowonganPklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
