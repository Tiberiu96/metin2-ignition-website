<?php

namespace App\Models\Web;

use App\Enums\EventAction;
use App\Enums\EventTrigger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameEventLog extends Model
{
    public const UPDATED_AT = null;

    protected $connection = 'mysql';

    protected $table = 'game_event_logs';

    protected $fillable = [
        'game_event_id',
        'action',
        'params_snapshot',
        'triggered_by',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'action' => EventAction::class,
            'triggered_by' => EventTrigger::class,
            'params_snapshot' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<GameEvent, $this> */
    public function event(): BelongsTo
    {
        return $this->belongsTo(GameEvent::class, 'game_event_id');
    }

    /** @return BelongsTo<Admin, $this> */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
}
