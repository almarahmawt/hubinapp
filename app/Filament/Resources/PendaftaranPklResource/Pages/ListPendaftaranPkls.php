<?php

namespace App\Filament\Resources\PendaftaranPklResource\Pages;

use App\Filament\Resources\PendaftaranPklResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPendaftaranPkls extends ListRecords
{
    protected static string $resource = PendaftaranPklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
