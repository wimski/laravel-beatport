<?php

namespace Wimski\Beatport\Data\Traits;

use Illuminate\Support\Collection;
use Wimski\Beatport\Data\Artist;

trait HasArtists
{
    /**
     * @var Collection
     */
    protected $artists;

    public function getArtists(): Collection
    {
        return $this->artists ?? new Collection();
    }

    public function setArtists(Collection $artists): self
    {
        $this->artists = $artists->filter(function ($artist) {
            return $artist instanceof Artist;
        });

        return $this;
    }

    public function addArtist(Artist $artist): self
    {
        if (! $this->artists) {
            $this->artists = new Collection();
        }

        $this->artists->push($artist);

        return $this;
    }
}
