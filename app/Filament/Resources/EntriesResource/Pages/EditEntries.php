<?php

namespace App\Filament\Resources\EntriesResource\Pages;

use App\Filament\Resources\EntriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntries extends EditRecord
{
    protected static string $resource = EntriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
