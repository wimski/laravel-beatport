<?php

namespace Wimski\Beatport\Data\Traits;

use Illuminate\Support\Collection;
use Wimski\Beatport\Data\Release;

trait HasReleases
{
    /**
     * @var Collection
     */
    protected $releases;

    public function getReleases(): Collection
    {
        return $this->releases ?? new Collection();
    }

    public function setReleases(Collection $releases): self
    {
        $this->releases = $releases->filter(function ($release) {
            return $release instanceof Release;
        });

        return $this;
    }

    public function addRelease(Release $release): self
    {
        if (! $this->releases) {
            $this->releases = new Collection();
        }

        $this->releases->push($release);

        return $this;
    }
}
