<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EventAction: string implements HasColor, HasLabel
{
    case Activated = 'activated';
    case Deactivated = 'deactivated';
    case Scheduled = 'scheduled';
    case ParamChanged = 'param_changed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Activated => 'Activated',
            self::Deactivated => 'Deactivated',
            self::Scheduled => 'Scheduled',
            self::ParamChanged => 'Param changed',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Activated => 'success',
            self::Deactivated => 'gray',
            self::Scheduled => 'info',
            self::ParamChanged => 'warning',
        };
    }
}
