<?php

namespace App\Filament\Pages;

use App\Filament\Imports\EntryImporter;
use Filament\Actions\ImportAction;
use Filament\Pages\Page;

class Import extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static string $view = 'filament.pages.import';

    protected static ?string $title = 'Import Bank Entries';

    protected static ?string $navigationLabel = 'Import';

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(EntryImporter::class)
                ->button()
                ->color('primary'),
        ];
    }
}
