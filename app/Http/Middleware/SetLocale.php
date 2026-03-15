<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected const SUPPORTED = ['en', 'de', 'hu', 'fr', 'cs', 'da', 'es', 'el', 'it', 'nl', 'pl', 'pt', 'ro', 'ru', 'tr'];

    protected const BROWSER_MAP = [
        'cz' => 'cs',
        'dk' => 'da',
        'gr' => 'el',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return $next($request);
    }

    protected function resolveLocale(Request $request): string
    {
        if ($request->has('lang')) {
            $lang = strtolower($request->query('lang'));
            $lang = self::BROWSER_MAP[$lang] ?? $lang;

            if (in_array($lang, self::SUPPORTED, true)) {
                return $lang;
            }
        }

        if (session()->has('locale')) {
            return session('locale');
        }

        foreach ($this->parseAcceptLanguage($request) as $lang) {
            $lang = self::BROWSER_MAP[$lang] ?? $lang;

            if (in_array($lang, self::SUPPORTED, true)) {
                return $lang;
            }
        }

        return 'en';
    }

    /** @return string[] */
    protected function parseAcceptLanguage(Request $request): array
    {
        $header = $request->header('Accept-Language', '');
        $locales = [];

        foreach (explode(',', $header) as $part) {
            $tag = strtolower(trim(explode(';', $part)[0]));
            $primary = explode('-', $tag)[0];

            if ($primary !== '') {
                $locales[] = $primary;
            }
        }

        return array_unique($locales);
    }
}
