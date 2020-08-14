<?php

namespace Wimski\Beatport\Tests\Stubs\Classes;

use Wimski\Beatport\Resources\Traits\ResourceInterfaceTrait;

class UseResourceInterfaceTrait
{
    use ResourceInterfaceTrait;

    public static function test(): void
    {
        static::checkIfResourceInterface();
    }
}
