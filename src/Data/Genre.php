<?php

namespace Wimski\Beatport\Data;

use Illuminate\Support\Collection;
use Wimski\Beatport\Data\Traits\HasReleases;
use Wimski\Beatport\Data\Traits\HasTracks;

class Genre extends AbstractData
{
    use HasReleases;
    use HasTracks;

    /**
     * @var Collection
     */
    protected $subGenres;

    public function getSubGenres(): Collection
    {
        return $this->subGenres ?? new Collection();
    }

    public function setSubGenres(Collection $subGenres): self
    {
        $this->subGenres = $subGenres->filter(function ($subGenre) {
            return $subGenre instanceof SubGenre;
        });

        return $this;
    }

    public function addSubGenre(SubGenre $subGenre): self
    {
        if (! $this->subGenres) {
            $this->subGenres = new Collection();
        }

        $this->subGenres->push($subGenre);

        return $this;
    }
}
