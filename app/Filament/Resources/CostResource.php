<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CostResource\Pages;
use App\Filament\Resources\CostResource\RelationManagers;
use App\Models\Cost;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CostResource extends Resource
{
    protected static ?string $model = Cost::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //card
                Card::make()->schema([
                    //project
                    Select::make('project_id')
                        ->label('Project')
                        ->relationship('project', 'name')
                        ->required(),

                    //grid
                    Grid::make(4)->schema([
                        //name
                        TextInput::make('name')
                            ->label('Cost Name')
                            ->placeholder('Cost Name')
                            ->required(),

                        // price
                        TextInput::make('price')
                            ->label('Price')
                            ->placeholder('IDR')
                            ->prefix('Rp. ')
                            ->numeric()
                            ->minValue(1)
                            ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 0)
                            ->required(),

                        // qty
                        TextInput::make('qty')
                            ->label('Qty')
                            ->placeholder('Input Qty')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        // date
                        DatePicker::make('date')
                            ->label('Date')
                            ->required(),
                    ]),

                    //image
                    FileUpload::make('image')
                        ->label('Image')
                        ->image()
                        ->imageEditor()
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')->searchable(),
                TextColumn::make('date'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('price')->currency('IDR'),
                TextColumn::make('qty')->searchable(),
                TextColumn::make('total_price')->currency('IDR'),
                ImageColumn::make('image')->circular(),
            ])
            ->defaultSort('updated_at')
            ->filters([
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListCosts::route('/'),
            'create' => Pages\CreateCost::route('/create'),
            'edit' => Pages\EditCost::route('/{record}/edit'),
        ];
    }
}
