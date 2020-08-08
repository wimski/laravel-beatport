<?php

namespace Wimski\Beatport\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static RequestTypeEnum INDEX()
 * @method static RequestTypeEnum RELATIONSHIP()
 * @method static RequestTypeEnum QUERY()
 * @method static RequestTypeEnum VIEW()
 */
class RequestTypeEnum extends Enum
{
    public const INDEX        = 'index';
    public const RELATIONSHIP = 'relationship';
    public const QUERY        = 'query';
    public const VIEW         = 'view';
}
