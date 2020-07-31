<?php

namespace Wimski\Beatport\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static RequestPageSizeEnum PAGE_SIZE_25()
 * @method static RequestPageSizeEnum PAGE_SIZE_50()
 * @method static RequestPageSizeEnum PAGE_SIZE_100()
 * @method static RequestPageSizeEnum PAGE_SIZE_150()
 */
class RequestPageSizeEnum extends Enum
{
    public const PAGE_SIZE_25  = 25;
    public const PAGE_SIZE_50  = 50;
    public const PAGE_SIZE_100 = 100;
    public const PAGE_SIZE_150 = 150;
}
