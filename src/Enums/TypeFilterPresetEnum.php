<?php

namespace Wimski\Beatport\Enums;

/**
 * @method static TypeFilterPresetEnum ALBUM()
 * @method static TypeFilterPresetEnum RELEASE()
 * @method static TypeFilterPresetEnum MIX()
 */
class TypeFilterPresetEnum extends AbstractEnum
{
    public const ALBUM   = 'Album';
    public const RELEASE = 'Release';
    public const MIX     = 'Mix';
}
