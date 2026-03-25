<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EventCategory: string implements HasColor, HasLabel
{
    case Simple = 'simple';
    case MultiParam = 'multi_param';
    case Complex = 'complex';

    public function getLabel(): string
    {
        return match ($this) {
            self::Simple => 'Simple',
            self::MultiParam => 'Multi-param',
            self::Complex => 'Complex',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Simple => 'success',
            self::MultiParam => 'warning',
            self::Complex => 'danger',
        };
    }
}
