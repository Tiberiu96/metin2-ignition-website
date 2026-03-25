<?php

use App\Models\Web\GameEventSchedule;
use App\Services\EventService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Game Event Scheduler
|--------------------------------------------------------------------------
|
| Runs every minute to start and stop game events based on their schedules.
| Ensure the cron daemon has: * * * * * php artisan schedule:run >> /dev/null
|
*/

Schedule::call(function (): void {
    $service = app(EventService::class);

    // Start events whose start_at has passed and haven't been started yet
    GameEventSchedule::query()
        ->where('start_at', '<=', now())
        ->where('started', false)
        ->with('event')
        ->each(function (GameEventSchedule $schedule) use ($service): void {
            $success = $service->activate(
                $schedule->event,
                $schedule->params_override ?? [],
            );

            $schedule->update(['started' => true]);

            if (! $success) {
                Log::error("Scheduler: failed to activate event [{$schedule->event->slug}] for schedule #{$schedule->id}");
            }
        });

    // Stop events whose stop_at has passed and haven't been stopped yet
    GameEventSchedule::query()
        ->whereNotNull('stop_at')
        ->where('stop_at', '<=', now())
        ->where('started', true)
        ->where('stopped', false)
        ->with('event')
        ->each(function (GameEventSchedule $schedule) use ($service): void {
            $success = $service->deactivate($schedule->event);

            $schedule->update(['stopped' => true]);

            if (! $success) {
                Log::error("Scheduler: failed to deactivate event [{$schedule->event->slug}] for schedule #{$schedule->id}");
            }
        });
})->everyMinute()->name('game-event-scheduler')->withoutOverlapping();
