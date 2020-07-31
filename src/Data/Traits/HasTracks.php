<?php

namespace Wimski\Beatport\Data\Traits;

use Illuminate\Support\Collection;
use Wimski\Beatport\Data\Track;

trait HasTracks
{
    /**
     * @var Collection
     */
    protected $tracks;

    public function getTracks(): Collection
    {
        return $this->tracks ?? new Collection();
    }

    public function setTracks(Collection $tracks): self
    {
        $this->tracks = $tracks->filter(function ($track) {
            return $track instanceof Track;
        });

        return $this;
    }

    public function addTrack(Track $track): self
    {
        if (! $this->tracks) {
            $this->tracks = new Collection();
        }

        $this->tracks->push($track);

        return $this;
    }
}
