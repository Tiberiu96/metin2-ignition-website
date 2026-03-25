<?php

namespace App\Services;

use App\Enums\EventAction;
use App\Enums\EventTrigger;
use App\Models\Web\GameEvent;
use App\Models\Web\GameEventLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventService
{
    public function __construct(
        private readonly GameAdminSocket $socket,
    ) {}

    /**
     * Activate a game event, sending all flags to the game server.
     *
     * @param  array<string, int|string>  $paramsOverride
     */
    public function activate(GameEvent $event, array $paramsOverride = []): bool
    {
        $params = array_merge($event->params ?? [], $paramsOverride);

        // Set the primary event flag to 1
        $success = $this->setFlag($event->quest_flag, 1);

        if (! $success) {
            Log::error("EventService: failed to activate [{$event->slug}] — socket error on primary flag");

            return false;
        }

        // Set additional parameter flags
        foreach ($params as $flag => $value) {
            $this->setFlag((string) $flag, $value);
        }

        $event->update(['is_active' => true, 'params' => array_merge($event->params ?? [], $paramsOverride)]);

        GameEventLog::query()->create([
            'game_event_id' => $event->id,
            'action' => EventAction::Activated,
            'params_snapshot' => array_merge([$event->quest_flag => 1], $params),
            'triggered_by' => Auth::check() ? EventTrigger::Manual : EventTrigger::Scheduler,
            'user_id' => Auth::id(),
        ]);

        return true;
    }

    /**
     * Deactivate a game event by setting its primary flag to 0.
     */
    public function deactivate(GameEvent $event): bool
    {
        $success = $this->setFlag($event->quest_flag, 0);

        if (! $success) {
            Log::error("EventService: failed to deactivate [{$event->slug}] — socket error");

            return false;
        }

        $event->update(['is_active' => false]);

        GameEventLog::query()->create([
            'game_event_id' => $event->id,
            'action' => EventAction::Deactivated,
            'params_snapshot' => [$event->quest_flag => 0],
            'triggered_by' => Auth::check() ? EventTrigger::Manual : EventTrigger::Scheduler,
            'user_id' => Auth::id(),
        ]);

        return true;
    }

    /**
     * Send a single flag change to the game server.
     */
    private function setFlag(string $flag, int|string $value): bool
    {
        return $this->socket->setEventFlag($flag, $value);
    }
}
