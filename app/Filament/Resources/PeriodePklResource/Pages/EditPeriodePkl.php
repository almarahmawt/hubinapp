<?php

namespace App\Filament\Resources\PeriodePklResource\Pages;

use App\Filament\Resources\PeriodePklResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeriodePkl extends EditRecord
{
    protected static string $resource = PeriodePklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
