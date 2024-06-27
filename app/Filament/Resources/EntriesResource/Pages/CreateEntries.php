<?php

namespace App\Filament\Resources\EntriesResource\Pages;

use App\Filament\Resources\EntriesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEntries extends CreateRecord
{
    protected static string $resource = EntriesResource::class;
}
