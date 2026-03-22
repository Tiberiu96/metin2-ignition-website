<?php

namespace App\Filament\Resources\CoinPackage\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoinPackageTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('coins')
                    ->label('Coins')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('price_eur')
                    ->label('Price (EUR)')
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('price_eur_original')
                    ->label('Original Price (EUR)')
                    ->money('EUR')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
