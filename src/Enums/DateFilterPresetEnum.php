<?php

namespace Wimski\Beatport\Enums;

/**
 * @method static DateFilterPresetEnum TODAY()
 * @method static DateFilterPresetEnum YESTERDAY()
 * @method static DateFilterPresetEnum LAST_WEEK()
 * @method static DateFilterPresetEnum LAST_MONTH()
 */
class DateFilterPresetEnum extends AbstractEnum
{
    public const TODAY      = '0d';
    public const YESTERDAY  = '1d';
    public const LAST_WEEK  = '7d';
    public const LAST_MONTH = '30d';
}
