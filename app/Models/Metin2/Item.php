<?php

namespace App\Models\Metin2;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $connection = 'player';

    protected $table = 'item';

    public $timestamps = false;

    protected $fillable = [
        'vnum',
        'count',
        'window',
        'pos',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'owner_id');
    }
}
