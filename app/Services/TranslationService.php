<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationService
{
    /** @var array<string> */
    private const SUPPORTED_LOCALES = [
        'en', 'de', 'hu', 'fr', 'cs', 'da', 'es', 'el', 'it', 'nl', 'pl', 'pt', 'ro', 'ru', 'tr',
    ];

    /**
     * Auto-translate missing locales for translatable fields.
     *
     * @param  array<string, mixed>  $data  Form data with fields like title.en, body.de, etc.
     * @param  array<string>  $translatableFields  Fields to auto-translate (e.g. ['title', 'excerpt', 'body'])
     * @return array<string, mixed>
     */
    public static function translateMissing(array $data, array $translatableFields): array
    {
        foreach ($translatableFields as $field) {
            $sourceLocale = self::detectSourceLocale($data, $field);

            if ($sourceLocale === null) {
                continue;
            }

            $sourceText = $data[$field][$sourceLocale];

            foreach (self::SUPPORTED_LOCALES as $locale) {
                if ($locale === $sourceLocale) {
                    continue;
                }

                if (self::hasContent($data[$field][$locale] ?? null)) {
                    continue;
                }

                try {
                    $translated = GoogleTranslate::trans($sourceText, $locale, $sourceLocale);

                    if (self::hasContent($translated)) {
                        $data[$field][$locale] = $translated;
                    } else {
                        Log::warning("Auto-translation returned empty for {$field} [{$sourceLocale} -> {$locale}]");
                    }
                } catch (\Throwable $e) {
                    Log::warning("Auto-translation failed for {$field} [{$sourceLocale} -> {$locale}]", [
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return $data;
    }

    /**
     * Find the first locale that has content for a given field.
     */
    private static function detectSourceLocale(array $data, string $field): ?string
    {
        if (! isset($data[$field]) || ! is_array($data[$field])) {
            return null;
        }

        foreach (self::SUPPORTED_LOCALES as $locale) {
            if (self::hasContent($data[$field][$locale] ?? null)) {
                return $locale;
            }
        }

        return null;
    }

    /**
     * Check if a value has real content (not just empty HTML tags or whitespace).
     */
    private static function hasContent(mixed $value): bool
    {
        if ($value === null || $value === '' || $value === false) {
            return false;
        }

        $stripped = trim(strip_tags((string) $value));

        return $stripped !== '';
    }
}
