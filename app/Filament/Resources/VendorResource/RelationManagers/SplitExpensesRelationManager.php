<?php

namespace App\Filament\Resources\VendorResource\RelationManagers;

use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SplitExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'split_expenses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                TextInput::make('amount')
                    ->prefix('$')
                    ->numeric()
                    ->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::where('type', 'debit')->get()->pluck('title', 'id')),
                TextInput::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                TextColumn::make('amount')
                    ->money('USD'),
                TextColumn::make('category.title'),
                TextColumn::make('note'),
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
