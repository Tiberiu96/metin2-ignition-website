<?php

namespace App\Hashing;

use Illuminate\Contracts\Hashing\Hasher;

class MysqlPasswordHasher implements Hasher
{
    public function make($value, array $options = []): string
    {
        return '*'.strtoupper(sha1(sha1($value, true)));
    }

    public function check($value, $hashedValue, array $options = []): bool
    {
        return $this->make($value) === strtoupper($hashedValue);
    }

    public function needsRehash($hashedValue, array $options = []): bool
    {
        return false;
    }

    public function info($hashedValue): array
    {
        return ['algo' => null];
    }
}
