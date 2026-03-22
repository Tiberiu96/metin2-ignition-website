<?php

namespace App\Filament\Resources\ShopCategory\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShopCategoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable(query: fn ($query, string $direction) => $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) {$direction}"))
                    ->searchable(query: fn ($query, string $search) => $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$search}%"]))
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['en'] ?? '') : $state),

                TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('icon'),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->sortable(),

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
