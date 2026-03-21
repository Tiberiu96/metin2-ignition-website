<?php

namespace App\Models\Metin2;

use Illuminate\Database\Eloquent\Model;

class ItemAward extends Model
{
    protected $connection = 'player';

    protected $table = 'item_award';

    public $timestamps = false;

    protected $fillable = [
        'pid',
        'login',
        'vnum',
        'count',
        'given_time',
        'why',
        'mall',
        'socket0',
        'socket1',
        'socket2',
    ];
}
