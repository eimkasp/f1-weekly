<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverResource\Pages;
use App\Models\Driver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'F1 Data';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('abbr')
                            ->label('Abbreviation')
                            ->maxLength(3),
                        Forms\Components\TextInput::make('number')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(99),
                        Forms\Components\Select::make('team_id')
                            ->relationship('team', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Personal Details')
                    ->schema([
                        Forms\Components\TextInput::make('nationality')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('country_code')
                            ->maxLength(3),
                        Forms\Components\DatePicker::make('date_of_birth'),
                        Forms\Components\TextInput::make('place_of_birth')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Career Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('world_championships')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('race_wins')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('podiums')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('pole_positions')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('fastest_laps')
                            ->numeric()
                            ->default(0),
                    ])->columns(5),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('drivers'),
                        Forms\Components\RichEditor::make('biography')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        Forms\Components\TextInput::make('external_id')
                            ->numeric()
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular(),
                Tables\Columns\TextColumn::make('number')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('abbr')
                    ->badge(),
                Tables\Columns\TextColumn::make('team.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nationality')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('world_championships')
                    ->label('WDC')
                    ->sortable(),
                Tables\Columns\TextColumn::make('race_wins')
                    ->label('Wins')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('team')
                    ->relationship('team', 'name'),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }
}
