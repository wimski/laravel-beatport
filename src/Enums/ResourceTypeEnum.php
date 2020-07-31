<?php

namespace Wimski\Beatport\Enums;

use Illuminate\Support\Str;
use MyCLabs\Enum\Enum;

/**
 * @method static ResourceTypeEnum ARTIST()
 * @method static ResourceTypeEnum GENRE()
 * @method static ResourceTypeEnum LABEL()
 * @method static ResourceTypeEnum RELEASE()
 * @method static ResourceTypeEnum TRACK()
 */
class ResourceTypeEnum extends Enum
{
    public const ARTIST    = 'artist';
    public const GENRE     = 'genre';
    public const LABEL     = 'label';
    public const RELEASE   = 'release';
    public const TRACK     = 'track';

    public function getValuePlural(): string
    {
        return Str::plural($this->getValue());
    }
}
