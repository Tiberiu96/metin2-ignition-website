<?php

namespace App\Models\Metin2;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    protected $connection = 'account';

    protected $table = 'accounts';

    public $timestamps = false;

    protected $fillable = [
        'login',
        'password',
        'email',
        'social_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPassword(): string
    {
        return $this->password;
    }
}
