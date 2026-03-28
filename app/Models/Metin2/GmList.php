<?php

namespace App\Models\Metin2;

use Illuminate\Database\Eloquent\Model;

class GmList extends Model
{
    protected $connection = 'common';

    protected $table = 'gmlist';

    public $timestamps = false;

    protected $primaryKey = 'mID';

    /**
     * Returns account logins that are staff (any authority except PLAYER).
     *
     * @return array<int, string>
     */
    public static function staffLogins(): array
    {
        return static::query()
            ->where('mAuthority', '!=', 'PLAYER')
            ->pluck('mAccount')
            ->all();
    }
}
