<?php

namespace App\Filament\Resources\ToProcessResource\Pages;

use App\Filament\Resources\ToProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntries extends ListRecords
{
    protected static string $resource = ToProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
