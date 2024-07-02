<?php

namespace App\Filament\Resources;

use App\Filament\Imports\EntryImporter;
use App\Filament\Resources\ToProcessResource\Pages;
use App\Models\Category;
use App\Models\Entry;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ToProcessResource extends Resource
{
    protected static ?string $model = Entry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationParentItem = 'Bank Entries';

    protected static ?string $navigationLabel = 'To Process';

    protected static ?string $modelLabel = 'Entry';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')
                    ->required(),
                TextInput::make('credit_amount')
                    ->numeric()
                    ->default(0)
                    ->disabled()
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
                    ->label('Debit')
                    ->sortable(),
                TextColumn::make('credit_amount')
                    ->money('USD')
                    ->label('Credit')
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->visibleFrom('2xl'),
                TextColumn::make('memo')
                    ->searchable()
                    ->visibleFrom('2xl'),
                Tables\Columns\SelectColumn::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('title', 'id')->toArray())
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
                    ->alignCenter(),

            ])
            ->filters([

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
            ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('category_id'))
            ->headerActions([
                ImportAction::make()
                    ->importer(EntryImporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntries::route('/'),
            'edit' => Pages\EditEntry::route('/{record}/edit'),
        ];
    }

    public static function getActions(): array
    {
        return [];
    }
}
