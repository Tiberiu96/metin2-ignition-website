<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EventTrigger: string implements HasLabel
{
    case Manual = 'manual';
    case Scheduler = 'scheduler';

    public function getLabel(): string
    {
        return match ($this) {
            self::Manual => 'Manual',
            self::Scheduler => 'Scheduler',
        };
    }
}
