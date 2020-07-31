<?php

namespace Wimski\Beatport\Traits;

use Khill\Duration\Duration;

trait ParsesDuration
{
    /**
     * @param int|string $duration
     * @return int
     */
    protected function parseDuration($duration): int
    {
        if (is_int($duration)) {
            return $duration;
        }

        return (new Duration($duration))->toSeconds();
    }

    protected function formatDuration(int $duration): string
    {
        return (new Duration($duration))->formatted();
    }
}
