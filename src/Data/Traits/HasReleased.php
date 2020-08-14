<?php

namespace Wimski\Beatport\Data\Traits;

use Carbon\Carbon;
use Wimski\Beatport\Traits\ParsesDate;

trait HasReleased
{
    use ParsesDate;

    /**
     * @var Carbon
     */
    protected $released;

    public function getReleased(): ?Carbon
    {
        return $this->released;
    }

    /**
     * @param Carbon|string|null $released
     * @return $this
     */
    public function setReleased($released = null): self
    {
        $this->released = $released ? $this->parseDate($released): null;

        return $this;
    }
}
