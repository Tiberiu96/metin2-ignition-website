<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponUsage extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'coupon_id',
        'account_id',
        'times_used',
    ];

    protected function casts(): array
    {
        return [
            'times_used' => 'integer',
        ];
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}
