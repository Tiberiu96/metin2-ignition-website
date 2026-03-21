<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopItem extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'shop_category_id',
        'vnum',
        'name',
        'description',
        'price',
        'price_original',
        'count',
        'sort_order',
        'is_active',
        'is_hot',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_hot' => 'boolean',
            'price' => 'integer',
            'price_original' => 'integer',
            'count' => 'integer',
            'vnum' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ShopCategory::class, 'shop_category_id');
    }

    public function getIconUrlAttribute(): ?string
    {
        $iconsUrl = config('services.shop.icons_url');

        if (! $iconsUrl || ! $this->vnum) {
            return null;
        }

        $iconsUrl = rtrim($iconsUrl, '/');
        $iconsPath = config('services.shop.icons_path', '/var/www/patches/webshop_icons');

        $candidates = [
            str_pad((string) $this->vnum, 5, '0', STR_PAD_LEFT),
            (string) $this->vnum,
        ];

        $baseVnum = (int) floor($this->vnum / 10) * 10;
        if ($baseVnum !== $this->vnum) {
            $candidates[] = str_pad((string) $baseVnum, 5, '0', STR_PAD_LEFT);
            $candidates[] = (string) $baseVnum;
        }

        foreach ($candidates as $name) {
            if (file_exists($iconsPath.'/'.$name.'.png')) {
                return $iconsUrl.'/'.$name.'.png';
            }
        }

        return $iconsUrl.'/'.str_pad((string) $this->vnum, 5, '0', STR_PAD_LEFT).'.png';
    }
}
