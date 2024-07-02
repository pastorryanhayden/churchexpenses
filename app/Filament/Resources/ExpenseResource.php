<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers\SplitExpensesRelationManager;
use App\Models\Category;
use App\Models\Entry;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExpenseResource extends Resource
{
    protected static ?string $model = Entry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationParentItem = 'Bank Entries';

    protected static ?string $navigationLabel = 'Expenses';

    protected static ?string $modelLabel = 'Expense Entry';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')
                    ->required(),
                TextInput::make('debit_amount')
                    ->numeric()
                    ->disabled()
                    ->default(0)
                    ->prefix('$'),
                TextInput::make('description'),
                TextInput::make('memo'),
                Select::make('vendor_id')
                    ->relationship(name: 'vendor', titleAttribute: 'name')
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()]),
                Select::make('category_id')
                    ->options(Category::where('type', 'credit')->pluck('title', 'id'))
                    ->preload()
                    ->searchable(),
                Toggle::make('split')
                    ->label('Split Item?')
                    ->columnSpanFull(),
                Toggle::make('is_pass_through')
                    ->label('Pass Through Item')
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
                TextColumn::make('debit_amount')
                    ->money('USD')
                    ->label('Amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->visibleFrom('2xl'),
                Tables\Columns\TextColumn::make('memo')
                    ->searchable()
                    ->visibleFrom('2xl'),
                Tables\Columns\SelectColumn::make('category_id')
                    ->label('Category')
                    ->options(Category::where('type', 'debit')->pluck('title', 'id')->toArray())
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
                    ->label('Non Budget Item'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::where('type', 'debit')->pluck('title', 'id')),
                TernaryFilter::make('split')
                    ->label('Split Status')
                    ->placeholder('All Entries')
                    ->trueLabel('Split Entries')
                    ->falseLabel('Non-split entries')
                    ->queries(
                        true: fn (Builder $query) => $query->where('split', 1),
                        false: fn (Builder $query) => $query->where('split', 0),
                        blank: fn (Builder $query) => $query
                    ),

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
            ->modifyQueryUsing(fn (Builder $query) => $query->where('debit_amount', '>', 0)->whereNotNull('category_id'));
    }

    public static function getRelations(): array
    {
        return [
            SplitExpensesRelationManager::class,
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
