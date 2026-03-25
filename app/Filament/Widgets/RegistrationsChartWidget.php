<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RegistrationsChartWidget extends ChartWidget
{
    protected ?string $heading = 'New Registrations';

    protected static ?int $sort = 3;

    /**
     * No polling — chart data is cached for 15 minutes.
     * Polling a 30/90-day aggregate on every tick would be wasteful.
     */
    protected ?string $pollingInterval = null;

    protected bool $isCollapsible = true;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
        ];
    }

    protected function getData(): array
    {
        $days = max(1, (int) ($this->filter ?? 30));

        return Cache::remember("dashboard_registrations_chart_{$days}", 900, function () use ($days): array {
            $raw = DB::connection('account')
                ->table('account')
                ->selectRaw('DATE(create_time) as date, COUNT(*) as cnt')
                ->where('create_time', '>=', now()->subDays($days - 1)->startOfDay())
                ->groupBy('date')
                ->pluck('cnt', 'date')
                ->all();

            $labels = [];
            $values = [];

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $labels[] = $days <= 30
                    ? now()->subDays($i)->format('d M')
                    : now()->subDays($i)->format('d M');
                $values[] = (int) ($raw[$date] ?? 0);
            }

            return [
                'datasets' => [
                    [
                        'label' => 'New Accounts',
                        'data' => $values,
                        'backgroundColor' => 'rgba(245, 158, 11, 0.4)',
                        'borderColor' => 'rgb(245, 158, 11)',
                        'borderWidth' => 2,
                        'borderRadius' => 4,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
            ],
        ];
    }
}
