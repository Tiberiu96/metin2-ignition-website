<?php

namespace App\Models\Metin2;

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

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
