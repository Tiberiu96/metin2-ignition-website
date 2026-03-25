<?php

namespace App\Filament\Widgets;

use App\Models\Web\GameEvent;
use App\Models\Web\GameEventSchedule;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveEventsWidget extends BaseWidget
{
    protected ?string $pollingInterval = '60s';

    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $total = GameEvent::query()->count();
        $active = GameEvent::query()->where('is_active', true)->count();
        $scheduled = GameEventSchedule::query()
            ->where('started', false)
            ->where('start_at', '>', now())
            ->count();

        return [
            Stat::make('Active Events', (string) $active)
                ->description("out of {$total} total events")
                ->descriptionIcon('heroicon-m-bolt')
                ->color($active > 0 ? 'success' : 'gray'),

            Stat::make('Upcoming Schedules', (string) $scheduled)
                ->description('pending to start')
                ->descriptionIcon('heroicon-m-clock')
                ->color($scheduled > 0 ? 'info' : 'gray'),
        ];
    }
}
