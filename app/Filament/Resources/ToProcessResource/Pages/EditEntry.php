<?php

namespace App\Filament\Resources\ToProcessResource\Pages;

use App\Filament\Resources\ToProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntry extends EditRecord
{
    protected static string $resource = ToProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
