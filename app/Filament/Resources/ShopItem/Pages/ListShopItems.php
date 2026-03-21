<?php

namespace App\Filament\Resources\ShopItem\Pages;

use App\Filament\Resources\ShopItem\ShopItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShopItems extends ListRecords
{
    protected static string $resource = ShopItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
