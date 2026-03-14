<?php

namespace App\Hashing;

use Illuminate\Contracts\Hashing\Hasher;

class MysqlPasswordHasher implements Hasher
{
    public function make(string $value, array $options = []): string
    {
        return '*'.strtoupper(sha1(sha1($value, true)));
    }

    public function check(string $value, string $hashedValue, array $options = []): bool
    {
        return $this->make($value) === strtoupper($hashedValue);
    }

    public function needsRehash(string $hashedValue, array $options = []): bool
    {
        return false;
    }

    public function info(string $hashedValue): array
    {
        return ['algo' => null];
    }
}
