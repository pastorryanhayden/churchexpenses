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

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                TextInput::make('debit_amount')
                    ->prefix('$')
                    ->numeric()
                    ->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::where('type', 'debit')->get()->pluck('title', 'id'))
                    ->searchable()
                    ->preload(),
                TextInput::make('memo')
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
                TextColumn::make('debit_amount')
                    ->money('USD'),
                TextColumn::make('category.title'),
                TextColumn::make('memo'),
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
