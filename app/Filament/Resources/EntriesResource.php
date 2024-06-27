<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntriesResource\Pages;
use App\Models\Category;
use App\Models\Entry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EntriesResource extends Resource
{
    protected static ?string $model = Entry::class;

    protected static ?string $navigationLabel = 'Bank Entries';

    protected static ?string $modelLabel = 'Entry';

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('date')
                    ->required(),
                Forms\Components\TextInput::make('credit_amount')
                    ->numeric()
                    ->default(0)
                    ->prefix('$')
                    ->hidden(function (?Model $record) {
                        return $record->credit_amount > 0 ? false : true;
                    })
                    ->disabled(),
                Forms\Components\TextInput::make('debit_amount')
                    ->disabled()
                    ->numeric()
                    ->prefix('$')
                    ->hidden(function (?Model $record) {
                        return $record->debit_amount > 0 ? false : true;
                    })
                    ->default(0),
                Forms\Components\TextInput::make('description'),
                Forms\Components\TextInput::make('memo'),
                Forms\Components\Select::make('vendor_id')
                    ->relationship(name: 'vendor', titleAttribute: 'name')
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()]),
                Forms\Components\Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'title')
                    ->preload()
                    ->required()
                    ->searchable(),
                Forms\Components\Toggle::make('split')
                    ->label('Split Item?')
                    ->columnSpanFull(),
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
                    ->state(function (Entry $record) {
                        if ($record->credit_amount) {
                            return '+$'.number_format($record->credit_amount, 2);
                        } elseif ($record->debit_amount) {
                            return '-$'.number_format($record->debit_amount, 2);
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
            'index' => Pages\ListEntries::route('/'),
            'create' => Pages\CreateEntries::route('/create'),
            'edit' => Pages\EditEntries::route('/{record}/edit'),
        ];
    }
}
