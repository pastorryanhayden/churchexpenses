<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Items'),
            'active' => Tab::make('Deposits')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('credit_ammount', '>', 0)),
            'inactive' => Tab::make('Debits')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('debit_ammount', '>', 0)),
        ];
    }
}
