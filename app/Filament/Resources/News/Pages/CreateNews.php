<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use App\Services\TranslationService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateNews extends CreateRecord
{
    private const LOCALES = ['en', 'de', 'hu', 'fr', 'cs', 'da', 'es', 'el', 'it', 'nl', 'pl', 'pt', 'ro', 'ru', 'tr'];

    protected static string $resource = NewsResource::class;

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
        $data = TranslationService::translateMissing($data, ['title', 'excerpt', 'body']);

        // Auto-generate slugs for each locale from translated titles
        $data = $this->generateSlugs($data);

        if ($before !== $data) {
            Notification::make()
                ->title('Missing translations were auto-filled')
                ->success()
                ->send();
        }

        return $data;
    }

    private function generateSlugs(array $data): array
    {
        foreach (self::LOCALES as $locale) {
            if (! empty($data['slug'][$locale])) {
                continue;
            }

            $title = $data['title'][$locale] ?? null;

            if ($title) {
                $data['slug'][$locale] = Str::slug($title).'-'.$locale;
            } else {
                $data['slug'][$locale] = Str::uuid()->toString().'-'.$locale;
            }
        }

        return $data;
    }
}
