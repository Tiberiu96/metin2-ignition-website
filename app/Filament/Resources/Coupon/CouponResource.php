<?php

namespace App\Filament\Resources\Coupon;

use App\Filament\Resources\Coupon\Pages\CreateCoupon;
use App\Filament\Resources\Coupon\Pages\EditCoupon;
use App\Filament\Resources\Coupon\Pages\ListCoupons;
use App\Filament\Resources\Coupon\Schemas\CouponForm;
use App\Filament\Resources\Coupon\Tables\CouponTable;
use App\Models\Web\Coupon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'Coupons';

    protected static string|UnitEnum|null $navigationGroup = 'Item Shop';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return CouponForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CouponTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
