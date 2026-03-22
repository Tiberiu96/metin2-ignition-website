<?php

namespace App\Filament\Resources\ShopItem\Pages;

use App\Filament\Resources\ShopItem\ShopItemResource;
use App\Services\TranslationService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateShopItem extends CreateRecord
{
    protected static string $resource = ShopItemResource::class;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->tooltip('Missing translations will be auto-translated. This may take a moment.');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
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
