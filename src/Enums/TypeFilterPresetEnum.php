<?php

namespace Wimski\Beatport\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static TypeFilterPresetEnum ALBUM()
 * @method static TypeFilterPresetEnum RELEASE()
 * @method static TypeFilterPresetEnum MIX()
 */
class TypeFilterPresetEnum extends Enum
{
    public const ALBUM   = 'Album';
    public const RELEASE = 'Release';
    public const MIX     = 'Mix';
}
