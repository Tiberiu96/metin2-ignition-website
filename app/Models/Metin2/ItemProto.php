<?php

namespace App\Models\Metin2;

use Illuminate\Database\Eloquent\Model;

class ItemProto extends Model
{
    protected $connection = 'player';

    protected $table = 'item_proto';

    protected $primaryKey = 'vnum';

    public $timestamps = false;

    public $incrementing = false;
}
