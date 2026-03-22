<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'code',
        'coins',
        'max_uses',
        'max_uses_per_user',
        'times_used',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'coins' => 'integer',
            'max_uses' => 'integer',
            'max_uses_per_user' => 'integer',
            'times_used' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_uses !== null && $this->times_used >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function incrementUsage(): void
    {
        $this->increment('times_used');
    }
}
