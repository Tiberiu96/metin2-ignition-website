<?php

namespace App\Models\Web;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'account_id',
        'type',
        'coins',
        'amount_eur',
        'currency',
        'coupon_code',
        'stripe_session_id',
        'status',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'status' => TransactionStatus::class,
            'coins' => 'integer',
            'amount_eur' => 'decimal:2',
        ];
    }
}
