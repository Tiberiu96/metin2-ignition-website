<?php

namespace App\Models\Web;

use App\Enums\EventCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameEvent extends Model
{
    protected $connection = 'mysql';

    protected $table = 'game_events';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'quest_flag',
        'is_active',
        'params',
    ];

    protected function casts(): array
    {
        return [
            'category' => EventCategory::class,
            'is_active' => 'boolean',
            'params' => 'array',
        ];
    }

    /** @return HasMany<GameEventSchedule, $this> */
    public function schedules(): HasMany
    {
        return $this->hasMany(GameEventSchedule::class);
    }

    /** @return HasMany<GameEventLog, $this> */
    public function logs(): HasMany
    {
        return $this->hasMany(GameEventLog::class)->latest('created_at');
    }
}
