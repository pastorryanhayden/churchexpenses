<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Models\Category;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('date')
                    ->required(),
                Forms\Components\TextInput::make('credit_ammount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('debit_ammount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('code')
                    ->numeric(),
                Forms\Components\TextInput::make('description'),
                Forms\Components\TextInput::make('reference')
                    ->numeric(),
                Forms\Components\TextInput::make('memo'),
                Forms\Components\TextInput::make('category_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('memo')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('title', 'id')->toArray())
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->state(function (Expense $record) {
                        if ($record->credit_ammount) {
                            return '+$'.number_format($record->credit_ammount, 2);
                        } elseif ($record->debit_ammount) {
                            return '-$'.number_format($record->debit_ammount, 2);
                        }

                        return '$0.00';
                    })
                    ->color(function ($record) {
                        return $record->credit_ammount ? 'success' : ($record->debit_ammount ? 'danger' : 'secondary');
                    }),

            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
