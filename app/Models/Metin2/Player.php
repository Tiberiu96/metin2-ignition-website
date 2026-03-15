<?php

namespace App\Models\Metin2;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    protected $connection = 'player';

    protected $table = 'player';

    public $timestamps = false;

    protected $fillable = [
        'level',
        'exp',
        'gold',
        'map_index',
        'x',
        'y',
    ];

    /** @var array<int, string> */
    protected const JOB_NAMES = [
        0 => 'Warrior',
        1 => 'Assassin',
        2 => 'Sura',
        3 => 'Shaman',
        4 => 'Warrior',
        5 => 'Assassin',
        6 => 'Sura',
        7 => 'Shaman',
    ];

    protected function jobName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::JOB_NAMES[$this->job] ?? 'Unknown',
        );
    }

    protected function playtimeHours(): Attribute
    {
        return Attribute::make(
            get: fn () => (int) ($this->playtime / 60),
        );
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
