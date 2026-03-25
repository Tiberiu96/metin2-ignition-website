<?php

namespace App\Filament\Widgets;

use App\Models\Web\GameEventSchedule;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingSchedulesWidget extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Event Schedules';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                GameEventSchedule::query()
                    ->with('event')
                    ->where(function (Builder $query): void {
                        $query->where(fn (Builder $q) => $q->where('started', false)->where('start_at', '>', now()))
                            ->orWhere(fn (Builder $q) => $q->where('started', true)->where('stopped', false)->whereNotNull('stop_at'));
                    })
                    ->orderBy('start_at')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('event.name')
                    ->label('Event')
                    ->searchable(),

                TextColumn::make('start_at')
                    ->label('Starts')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('stop_at')
                    ->label('Stops')
                    ->dateTime('d M Y H:i')
                    ->placeholder('No auto-stop')
                    ->sortable(),

                TextColumn::make('started')
                    ->label('Status')
                    ->getStateUsing(function (GameEventSchedule $record): string {
                        if ($record->started && ! $record->stopped) {
                            return 'Running';
                        }

                        return 'Pending';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Running' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('repeat_type')
                    ->label('Repeat')
                    ->badge()
                    ->color('info'),
            ])
            ->paginated(false);
    }
}
