<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    /**
     * @return array{amount: float, currency: string, formatted: string}
     */
    public function getDisplayPrice(float $priceEur, string $locale): array
    {
        $currency = config("coins.locale_currencies.{$locale}", 'EUR');

        if ($currency === 'EUR') {
            return [
                'amount' => $priceEur,
                'currency' => 'EUR',
                'formatted' => $this->format($priceEur, 'EUR'),
            ];
        }

        $rate = $this->getExchangeRate($currency);

        if ($rate === null) {
            return [
                'amount' => $priceEur,
                'currency' => 'EUR',
                'formatted' => $this->format($priceEur, 'EUR'),
            ];
        }

        $converted = round($priceEur * $rate, 2);

        return [
            'amount' => $converted,
            'currency' => $currency,
            'formatted' => $this->format($converted, $currency),
        ];
    }

    protected function getExchangeRate(string $currency): ?float
    {
        $rates = $this->getExchangeRates();

        return $rates[$currency] ?? null;
    }

    /**
     * @return array<string, float>
     */
    protected function getExchangeRates(): array
    {
        $cacheHours = config('coins.exchange_cache_hours', 6);

        return Cache::remember('exchange_rates_eur', $cacheHours * 3600, function () {
            try {
                $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/EUR');

                if ($response->successful()) {
                    return $response->json('rates', []);
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to fetch exchange rates: '.$e->getMessage());
            }

            return [];
        });
    }

    protected function format(float $amount, string $currency): string
    {
        $symbols = [
            'EUR' => "\u{20AC}",
            'USD' => '$',
            'GBP' => "\u{00A3}",
            'PLN' => ' zl',
            'CZK' => ' Kc',
            'HUF' => ' Ft',
            'RON' => ' RON',
            'DKK' => ' kr',
            'TRY' => ' TL',
            'RUB' => ' RUB',
        ];

        $symbol = $symbols[$currency] ?? " {$currency}";
        $prefixed = in_array($currency, ['USD', 'GBP', 'EUR']);

        if ($prefixed) {
            return $symbol.number_format($amount, 2, ',', '.');
        }

        return number_format($amount, 2, ',', '.').$symbol;
    }
}
