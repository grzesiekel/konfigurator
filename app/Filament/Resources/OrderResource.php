<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Sklep';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label('Numer')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn($record) => $record && $record->exists),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Oczekujące',
                        'processing' => 'W trakcie realizacji',
                        'completed' => 'Gotowe',
                        'closed' => 'Zakończone',
                        'declined' => 'Odrzucone',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->label('Produkt')
                    ->options(Product::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('email')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('phone')
                //     ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Oczekujące',
                        'processing' => 'W trakcie realizacji',
                        'completed' => 'Gotowe',
                        'closed' => 'Zakończone',
                        'declined' => 'Odrzucone',
                    ])
                    ->searchable()
                    ->disablePlaceholderSelection(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nazwa produktu')
                    ->searchable(),
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
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Oczekujące',
                        'processing' => 'W trakcie realizacji',
                        'completed' => 'Gotowe',
                        'closed' => 'Zakończone',
                        'declined' => 'Odrzucone',
                    ]),
                SelectFilter::make('product_id')
                    ->options(Product::pluck('name', 'id')),
                    Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Od daty'),
                        DatePicker::make('created_until')
                            ->label('Do daty'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
