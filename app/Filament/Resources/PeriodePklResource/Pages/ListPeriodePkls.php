<?php

namespace App\Filament\Resources\PeriodePklResource\Pages;

use App\Filament\Resources\PeriodePklResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeriodePkls extends ListRecords
{
    protected static string $resource = PeriodePklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
