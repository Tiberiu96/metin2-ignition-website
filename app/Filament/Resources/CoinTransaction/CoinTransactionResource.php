<?php

namespace App\Filament\Resources\CoinTransaction;

use App\Filament\Resources\CoinTransaction\Pages\ListCoinTransactions;
use App\Filament\Resources\CoinTransaction\Tables\CoinTransactionTable;
use App\Models\Web\CoinTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CoinTransactionResource extends Resource
{
    protected static ?string $model = CoinTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Coin Transactions';

    protected static string|UnitEnum|null $navigationGroup = 'Item Shop';

    protected static ?int $navigationSort = 5;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return CoinTransactionTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoinTransactions::route('/'),
        ];
    }
}
