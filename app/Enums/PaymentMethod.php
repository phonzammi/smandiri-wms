<?php

namespace App\Enums;

use Illuminate\Database\Eloquent\Collection;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case KREDIT = 'kredit';

    public function getLabelText(): string
    {
        return match ($this) {
            self::CASH => 'CASH',
            self::KREDIT => 'KREDIT',
        };
    }
}
