<?php

namespace App\Filament\Resources\CoinPackage;

use App\Filament\Resources\CoinPackage\Pages\CreateCoinPackage;
use App\Filament\Resources\CoinPackage\Pages\EditCoinPackage;
use App\Filament\Resources\CoinPackage\Pages\ListCoinPackages;
use App\Filament\Resources\CoinPackage\Schemas\CoinPackageForm;
use App\Filament\Resources\CoinPackage\Tables\CoinPackageTable;
use App\Models\Web\CoinPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CoinPackageResource extends Resource
{
    protected static ?string $model = CoinPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $navigationLabel = 'Coin Packages';

    protected static string|UnitEnum|null $navigationGroup = 'Item Shop';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CoinPackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoinPackageTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoinPackages::route('/'),
            'create' => CreateCoinPackage::route('/create'),
            'edit' => EditCoinPackage::route('/{record}/edit'),
        ];
    }
}
