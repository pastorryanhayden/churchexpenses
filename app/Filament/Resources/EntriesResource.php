<?php

namespace App\Filament\Resources;

use App\Filament\Imports\EntryImporter;
use App\Filament\Resources\EntriesResource\Pages;
use App\Filament\Resources\EntriesResource\RelationManagers\SplitExpensesRelationManager;
use App\Filament\Resources\EntriesResource\RelationManagers\SplitIncomesRelationManager;
use App\Models\Category;
use App\Models\Entry;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

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
                Forms\Components\DatePicker::make('date')
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
                    ->options(function (?Model $record) {
                        if ($record->credit_amount > 0) {
                            return Category::where('type', 'credit')->orWhere('type', 'pass-through')->pluck('title', 'id');
                        } elseif ($record->debit_amount > 0) {
                            return Category::where('type', 'debit')->orWhere('type', 'pass-through')->pluck('title', 'id');
                        }
                    })
                    ->preload()
                    ->searchable(),
                Toggle::make('split')
                    ->label('Split Item?')
                    ->columnSpanFull(),
                Toggle::make('is_pass_through')
                    ->label('Non-Budget Item')
                    ->helperText('This will not be included in any budget calcaulations.'),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->searchable()
                    ->sortable()
                    ->date(),
                TextColumn::make('credit_amount')
                    ->money('USD')
                    ->label('Credit Amount')
                    ->sortable()
                    ->hidden(Request::query('activeTab') == 'expenses'),
                TextColumn::make('debit_amount')
                    ->money('USD')
                    ->label('Debit Amount')
                    ->sortable()
                    ->hidden(Request::query('activeTab') == 'income'),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->visibleFrom('2xl'),
                Tables\Columns\TextColumn::make('memo')
                    ->searchable()
                    ->visibleFrom('2xl'),
                Tables\Columns\SelectColumn::make('category_id')
                    ->label('Category')
                    ->options(function (?Model $record) {
                        if ($record->credit_amount > 0) {
                            return Category::where('type', 'credit')->orWhere('type', 'pass-through')->pluck('title', 'id')->toArray();
                        } elseif ($record->debit_amount > 0) {
                            return Category::where('type', 'debit')->orWhere('type', 'pass-through')->pluck('title', 'id')->toArray();
                        }
                    })
                    ->sortable(),
                IconColumn::make('split')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->alignCenter(),
                IconColumn::make('is_pass_through')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrows-right-left')
                    ->falseIcon('heroicon-o-arrows-right-left')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->alignCenter()
                    ->label('Non-Budget Item'),

            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'title'),
            ])
            ->defaultGroup('category.title')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(EntryImporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SplitExpensesRelationManager::class,
            SplitIncomesRelationManager::class,
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
