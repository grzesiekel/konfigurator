<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttributeItemResource\Pages;
use App\Filament\Resources\AttributeItemResource\RelationManagers;
use App\Models\AttributeItem;
use App\Models\Attribute;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class AttributeItemResource extends Resource
{
    protected static ?string $model = AttributeItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Ustawienia';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('attribute_id')
                    ->label('Produkt')
                    ->options(Attribute::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->directory('img')
                    ->image()
                    ->maxSize(1024) // Maksymalnie 1MB
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attribute_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('attribute_id')
                    ->label('Attribute') // Etykieta filtra
                    ->relationship('attribute', 'name') // Relacja + pole wyświetlane w opcjach
                    ->searchable() // Opcjonalnie: wyszukiwanie
                    ->preload(),
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
            'index' => Pages\ListAttributeItems::route('/'),
            'create' => Pages\CreateAttributeItem::route('/create'),
            'edit' => Pages\EditAttributeItem::route('/{record}/edit'),
        ];
    }
}
