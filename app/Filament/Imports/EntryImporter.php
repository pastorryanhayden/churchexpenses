<?php

namespace App\Filament\Imports;

use App\Models\Entry;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class EntryImporter extends Importer
{
    protected static ?string $model = Entry::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('date')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('credit_amount')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('debit_amount')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('description'),
            ImportColumn::make('memo'),

        ];
    }

    public function resolveRecord(): ?Entry
    {
        // return Entry::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Entry();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your entry import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
