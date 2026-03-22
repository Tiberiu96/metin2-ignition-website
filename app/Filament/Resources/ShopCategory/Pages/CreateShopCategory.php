<?php

namespace App\Filament\Resources\ShopCategory\Pages;

use App\Filament\Resources\ShopCategory\ShopCategoryResource;
use App\Services\TranslationService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateShopCategory extends CreateRecord
{
    protected static string $resource = ShopCategoryResource::class;

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
        if (empty($data['slug'])) {
            $title = $this->resolveFirstTranslation($data, 'name');
            $data['slug'] = $title ? Str::slug($title) : Str::uuid()->toString();
        }

        $before = $data;
        $data = TranslationService::translateMissing($data, ['name']);

        if ($before !== $data) {
            Notification::make()
                ->title('Missing translations were auto-filled')
                ->success()
                ->send();
        }

        return $data;
    }

    private function resolveFirstTranslation(array $data, string $field): ?string
    {
        if (! isset($data[$field]) || ! is_array($data[$field])) {
            return null;
        }

        foreach ($data[$field] as $value) {
            if (! empty($value)) {
                return $value;
            }
        }

        return null;
    }
}
