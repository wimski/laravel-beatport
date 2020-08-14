<?php

namespace Wimski\Beatport\Enums;

use Illuminate\Support\Str;

/**
 * @method static ResourceTypeEnum ARTIST()
 * @method static ResourceTypeEnum GENRE()
 * @method static ResourceTypeEnum KEY()
 * @method static ResourceTypeEnum LABEL()
 * @method static ResourceTypeEnum RELEASE()
 * @method static ResourceTypeEnum SUB_GENRE()
 * @method static ResourceTypeEnum TRACK()
 */
class ResourceTypeEnum extends AbstractEnum
{
    public const ARTIST    = 'artist';
    public const GENRE     = 'genre';
    public const KEY       = 'key';
    public const LABEL     = 'label';
    public const RELEASE   = 'release';
    public const SUB_GENRE = 'sub-genre';
    public const TRACK     = 'track';

    public function getValuePlural(): string
    {
        return Str::plural($this->getValue());
    }
}
