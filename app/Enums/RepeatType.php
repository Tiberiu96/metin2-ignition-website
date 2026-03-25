<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RepeatType: string implements HasLabel
{
    case None = 'none';
    case Weekly = 'weekly';
    case Monthly = 'monthly';

    public function getLabel(): string
    {
        return match ($this) {
            self::None => 'No repeat',
            self::Weekly => 'Weekly',
            self::Monthly => 'Monthly',
        };
    }
}
