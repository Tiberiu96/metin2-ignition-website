<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AccountStatsWidget extends BaseWidget
{
    /**
     * No polling — these are DB-heavy queries, cache handles freshness.
     */
    protected ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $stats = Cache::remember('dashboard_account_stats', 600, function (): array {
            $db = DB::connection('account')->table('account');

            $total = (clone $db)->count();

            $today = (clone $db)
                ->whereDate('create_time', today())
                ->count();

            $activeToday = (clone $db)
                ->whereDate('last_play', today())
                ->count();

            $activeWeek = (clone $db)
                ->where('last_play', '>=', now()->subDays(7))
                ->count();

            $banned = (clone $db)
                ->where('status', '!=', 'OK')
                ->count();

            // Sparkline: registrations per day for last 7 days
            $raw = (clone $db)
                ->selectRaw('DATE(create_time) as date, COUNT(*) as cnt')
                ->where('create_time', '>=', now()->subDays(7)->startOfDay())
                ->groupBy('date')
                ->pluck('cnt', 'date')
                ->all();

            $sparkline = [];
            for ($i = 6; $i >= 0; $i--) {
                $sparkline[] = (int) ($raw[now()->subDays($i)->format('Y-m-d')] ?? 0);
            }

            return compact('total', 'today', 'activeToday', 'activeWeek', 'banned', 'sparkline');
        });

        return [
            Stat::make('Total Accounts', number_format($stats['total']))
                ->description('Registered players')
                ->descriptionIcon('heroicon-m-users')
                ->chart($stats['sparkline'])
                ->color('info'),

            Stat::make('Registered Today', (string) $stats['today'])
                ->description('New accounts today')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),

            Stat::make('Active Today', (string) $stats['activeToday'])
                ->description("{$stats['activeWeek']} active this week")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),

            Stat::make('Banned', (string) $stats['banned'])
                ->description('Suspended accounts')
                ->descriptionIcon('heroicon-m-no-symbol')
                ->color($stats['banned'] > 0 ? 'danger' : 'gray'),
        ];
    }
}
