<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;

class CoinPackage extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'coins',
        'price_eur',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'coins' => 'integer',
            'price_eur' => 'decimal:2',
        ];
    }
}
