<?php

namespace App\Filament\Resources\CoinPackage\Pages;

use App\Filament\Resources\CoinPackage\CoinPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCoinPackages extends ListRecords
{
    protected static string $resource = CoinPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
