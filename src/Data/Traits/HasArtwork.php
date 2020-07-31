<?php

namespace Wimski\Beatport\Data\Traits;

trait HasArtwork
{
    /**
     * @var string
     */
    protected $artwork;

    public function getArtwork(): ?string
    {
        return $this->artwork;
    }

    public function setArtwork(string $artwork): self
    {
        $this->artwork = $artwork;

        return $this;
    }
}
