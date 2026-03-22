<?php

namespace App\Filament\Resources\CoinPackage\Pages;

use App\Filament\Resources\CoinPackage\CoinPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCoinPackage extends EditRecord
{
    protected static string $resource = CoinPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
