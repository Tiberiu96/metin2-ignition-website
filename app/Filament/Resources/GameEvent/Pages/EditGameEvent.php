<?php

namespace App\Filament\Resources\GameEvent\Pages;

use App\Filament\Resources\GameEvent\GameEventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGameEvent extends EditRecord
{
    protected static string $resource = GameEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
