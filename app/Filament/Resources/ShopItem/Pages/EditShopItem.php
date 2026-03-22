<?php

namespace App\Filament\Resources\ShopItem\Pages;

use App\Filament\Resources\ShopItem\ShopItemResource;
use App\Services\TranslationService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditShopItem extends EditRecord
{
    protected static string $resource = ShopItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->tooltip('Missing translations will be auto-translated. This may take a moment.');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $before = $data;
        $data = TranslationService::translateMissing($data, ['name', 'description']);

        if ($before !== $data) {
            Notification::make()
                ->title('Missing translations were auto-filled')
                ->success()
                ->send();
        }

        return $data;
    }
}
