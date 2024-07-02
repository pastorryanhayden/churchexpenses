<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'income' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'credit')),
            'expenses' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'debit')),
            'pass-through' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'pass-through')),

        ];
    }
}
