<?php

namespace App\Filament\Resources\CoinPackage\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CoinPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('coins')
                ->label('Coins')
                ->required()
                ->numeric()
                ->minValue(1),

            TextInput::make('price_eur')
                ->label('Price (EUR)')
                ->required()
                ->numeric()
                ->minValue(0.01)
                ->step(0.01),

            TextInput::make('price_eur_original')
                ->label('Original Price (EUR)')
                ->numeric()
                ->minValue(0.01)
                ->step(0.01)
                ->nullable()
                ->helperText('Set higher than Price to show a strikethrough discount'),

            TextInput::make('sort_order')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }
}
