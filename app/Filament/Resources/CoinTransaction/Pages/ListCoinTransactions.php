<?php

namespace App\Filament\Resources\CoinTransaction\Pages;

use App\Filament\Resources\CoinTransaction\CoinTransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListCoinTransactions extends ListRecords
{
    protected static string $resource = CoinTransactionResource::class;
}
