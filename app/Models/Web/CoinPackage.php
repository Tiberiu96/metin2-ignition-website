<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;

class CoinPackage extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'coins',
        'price_eur',
        'price_eur_original',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'coins' => 'integer',
            'price_eur' => 'decimal:2',
            'price_eur_original' => 'decimal:2',
        ];
    }

    public function hasDiscount(): bool
    {
        return $this->price_eur_original !== null && $this->price_eur_original > $this->price_eur;
    }
}
