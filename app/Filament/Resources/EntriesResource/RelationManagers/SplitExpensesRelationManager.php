<?php

namespace App\Filament\Resources\EntriesResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SplitExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'split_expenses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->debit_amount > 0 && $ownerRecord->split == true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('cost')
            ->columns([
                Tables\Columns\TextColumn::make('cost'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
