<?php

namespace Wimski\Beatport\Data;

class SubGenre extends AbstractData
{
    /**
     * @var Genre
     */
    protected $genre;

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(Genre $genre = null): self
    {
        $this->genre = $genre;

        return $this;
    }

}
