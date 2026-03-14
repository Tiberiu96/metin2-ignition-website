<?php

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $connection = 'mysql';

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'excerpt',
        'published_at',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}
