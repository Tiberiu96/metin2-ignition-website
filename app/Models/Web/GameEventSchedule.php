<?php

namespace App\Models\Web;

use App\Enums\RepeatType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameEventSchedule extends Model
{
    protected $connection = 'mysql';

    protected $table = 'game_event_schedules';

    protected $fillable = [
        'game_event_id',
        'start_at',
        'stop_at',
        'started',
        'stopped',
        'repeat_type',
        'params_override',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'stop_at' => 'datetime',
            'started' => 'boolean',
            'stopped' => 'boolean',
            'repeat_type' => RepeatType::class,
            'params_override' => 'array',
        ];
    }

    /** @return BelongsTo<GameEvent, $this> */
    public function event(): BelongsTo
    {
        return $this->belongsTo(GameEvent::class, 'game_event_id');
    }
}
