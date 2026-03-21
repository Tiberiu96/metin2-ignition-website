<?php

namespace App\Filament\Resources\ShopItem;

use App\Filament\Resources\ShopItem\Pages\CreateShopItem;
use App\Filament\Resources\ShopItem\Pages\EditShopItem;
use App\Filament\Resources\ShopItem\Pages\ListShopItems;
use App\Filament\Resources\ShopItem\Schemas\ShopItemForm;
use App\Filament\Resources\ShopItem\Tables\ShopItemTable;
use App\Models\Web\ShopItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ShopItemResource extends Resource
{
    protected static ?string $model = ShopItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'Shop Items';

    protected static string|UnitEnum|null $navigationGroup = 'Item Shop';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ShopItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShopItemTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShopItems::route('/'),
            'create' => CreateShopItem::route('/create'),
            'edit' => EditShopItem::route('/{record}/edit'),
        ];
    }
}
