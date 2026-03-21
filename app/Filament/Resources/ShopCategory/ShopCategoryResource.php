<?php

namespace App\Filament\Resources\ShopCategory;

use App\Filament\Resources\ShopCategory\Pages\CreateShopCategory;
use App\Filament\Resources\ShopCategory\Pages\EditShopCategory;
use App\Filament\Resources\ShopCategory\Pages\ListShopCategories;
use App\Filament\Resources\ShopCategory\Schemas\ShopCategoryForm;
use App\Filament\Resources\ShopCategory\Tables\ShopCategoryTable;
use App\Models\Web\ShopCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ShopCategoryResource extends Resource
{
    protected static ?string $model = ShopCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $navigationLabel = 'Shop Categories';

    protected static string|UnitEnum|null $navigationGroup = 'Item Shop';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ShopCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShopCategoryTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShopCategories::route('/'),
            'create' => CreateShopCategory::route('/create'),
            'edit' => EditShopCategory::route('/{record}/edit'),
        ];
    }
}
