<?php

namespace App\Filament\Resources\EntriesResource\Pages;

use App\Filament\Resources\EntriesResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEntries extends ListRecords
{
    protected static string $resource = EntriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getCachedTabs(): array
    {
        return [
            //  'all' => Tab::make(),
            //  'income' => Tab::make()
            //      ->modifyQueryUsing(fn (Builder $query) => $query->where('credit_amount', '>', 0)),
            //  'expenses' => Tab::make()
            //      ->modifyQueryUsing(fn (Builder $query) => $query->where('debit_amount', '>', 0)),
            //  'to process' => Tab::make()
            //      ->modifyQueryUsing(fn (Builder $query) => $query->where('split', 0)->whereNull('category_id')),
        ];
    }
}
