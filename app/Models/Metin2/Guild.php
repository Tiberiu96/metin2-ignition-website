<?php

namespace App\Models\Metin2;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guild extends Model
{
    protected $connection = 'player';

    protected $table = 'guild';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'level',
    ];

    public function master(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'master');
    }
}
