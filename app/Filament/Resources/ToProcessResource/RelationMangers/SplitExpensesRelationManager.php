<?php

namespace App\Filament\Resources\ToProcessResource\RelationManagers;

use App\Models\Category;
use App\Models\Vendor;
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
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->options(Category::where('type', 'debit')->orWhere('type', 'to-process')->pluck('title', 'id')->toArray())
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('vendor_id')
                    ->label('Vendor')
                    ->options(Vendor::all()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('note')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\TextColumn::make('vendor.name'),
                Tables\Columns\TextColumn::make('category.title'),
                Tables\Columns\TextColumn::make('note'),            ])
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
