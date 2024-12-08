<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //card
                Card::make()->schema([
                    //name
                    TextInput::make('name')
                        ->label('Project Name')
                        ->placeholder('Project Name')
                        ->required(),

                    //description
                    Textarea::make('description')
                        ->label('Description')
                        ->placeholder('Description')
                        ->rows(2),

                    // modal
                    TextInput::make('modal')
                        ->label('Modal')
                        ->placeholder('IDR')
                        ->prefix('Rp. ')
                        ->numeric()
                        ->minValue(1)
                        ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 0)
                        ->required(),

                    //grid
                    Grid::make(columns: 3)->schema([
                        //category
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required(),

                        //location
                        Select::make('location_id')
                            ->label('Location')
                            ->relationship('location', 'name')
                            ->required(),

                        //user
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->required(),
                    ]),

                    //grid
                    Grid::make(2)->schema([
                        //start_period
                        DatePicker::make('start_period')
                            ->label('Start Period')
                            ->placeholder('Start Period')
                            ->required(),

                        //end_period
                        DatePicker::make('end_period')
                            ->label('End Period')
                            ->placeholder('End Period')
                            ->required(),
                    ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                // TextColumn::make('description')->searchable(),
                TextColumn::make('modal')->currency('IDR'),
                TextColumn::make('category.name')->searchable(),
                TextColumn::make('location.name')->searchable(),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('start_period'),
                TextColumn::make('end_period'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('viewCosts')
                    ->label('View Costs')
                    ->icon('heroicon-o-eye')
                    ->tooltip('View all related costs')
                    ->url(fn (Project $record) => $record->costs()->exists()
                        ? route('filament.tiomuhamadnur.resources.costs.index', [
                            'tableFilters[project_id][value]' => $record->id,
                        ])
                        : null) // URL hanya dihasilkan jika data Cost tersedia
                    ->hidden(fn (Project $record) => !$record->costs()->exists()), // Sembunyikan jika tidak ada data Cost
                EditAction::make(), // Tombol edit tetap ada
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
