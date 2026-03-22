<?php

namespace App\Filament\Resources\ShopItem\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ShopItemTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable(query: fn ($query, string $direction) => $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) {$direction}"))
                    ->searchable(query: fn ($query, string $search) => $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$search}%"]))
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['en'] ?? '') : $state)
                    ->limit(40),

                TextColumn::make('vnum')
                    ->label('VNUM')
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['en'] ?? '') : $state),

                TextColumn::make('price')
                    ->label('Price')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('price_original')
                    ->label('Original')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('count')
                    ->label('Qty')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                IconColumn::make('is_hot')
                    ->label('Hot')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('shop_category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                TernaryFilter::make('is_active')
                    ->label('Active'),

                TernaryFilter::make('is_hot')
                    ->label('Hot'),
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
