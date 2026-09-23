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
            Actions\DeleteAction::make()
                ->visible(fn () => JurnalPklResource::canEditJurnal($this->record)),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->visible(fn () => JurnalPklResource::canEditJurnal($this->record)),
            $this->getCancelFormAction(),
        ];
    }
}
