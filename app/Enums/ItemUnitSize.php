<?php

namespace App\Enums;

enum ItemUnitSize: string
{
    case LARGE = "L";
    case MEDIUM = "M";
    case SMALL = "S";

    public function isLarge(): bool
    {
        return $this === self::LARGE;
    }

    public function isMedium(): bool
    {
        return $this === self::MEDIUM;
    }

    public function isSmall(): bool
    {
        return $this === self::SMALL;
    }
}
