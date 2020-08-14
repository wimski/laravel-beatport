<?php

namespace Wimski\Beatport\Enums;

use MyCLabs\Enum\Enum;

abstract class AbstractEnum extends Enum
{
    public static function isValid($value): bool
    {
        if ($value instanceof static) {
            return true;
        }

        return parent::isValid($value);
    }
}
