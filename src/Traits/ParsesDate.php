<?php

namespace Wimski\Beatport\Traits;

use Carbon\Carbon;

trait ParsesDate
{
    /**
     * @param Carbon|string $date
     * @return Carbon
     */
    protected function parseDate($date): Carbon
    {
        if ($date instanceof Carbon) {
            return $date;
        }

        return Carbon::parse($date);
    }

    protected function formatDate(Carbon $date): string
    {
        return $date->format('Y-m-d');
    }
}
