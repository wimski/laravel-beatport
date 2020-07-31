<?php

namespace Wimski\Beatport\Data;

use Illuminate\Support\Collection;
use Wimski\Beatport\Data\Traits\HasArtists;
use Wimski\Beatport\Data\Traits\HasLabel;
use Wimski\Beatport\Data\Traits\HasReleased;
use Wimski\Beatport\Traits\ParsesDuration;

class Track extends AbstractData
{
    use HasArtists;
    use HasLabel;
    use HasReleased;
    use ParsesDuration;

    /**
     * @var string
     */
    protected $remix;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var int
     */
    protected $bpm;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var Genre
     */
    protected $genre;

    /**
     * @var SubGenre
     */
    protected $subGenre;

    /**
     * @var Release
     */
    protected $release;

    /**
     * @var Collection
     */
    protected $remixers;

    public function getRemix(): ?string
    {
        return $this->remix;
    }

    public function setRemix(string $remix): self
    {
        $this->remix = $remix;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int|string $length
     * @return $this
     */
    public function setLength($length): self
    {
        $this->length = $this->parseDuration($length);

        return $this;
    }

    public function getBpm(): ?int
    {
        return $this->bpm;
    }

    public function setBpm(int $bpm): self
    {
        $this->bpm = $bpm;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getSubGenre(): ?SubGenre
    {
        return $this->subGenre;
    }

    public function setSubGenre(SubGenre $subGenre): self
    {
        $this->subGenre = $subGenre;

        return $this;
    }

    public function getRelease(): ?Release
    {
        return $this->release;
    }

    public function setRelease(Release $release): self
    {
        $this->release = $release;

        return $this;
    }

    public function getRemixers(): Collection
    {
        return $this->remixers ?? new Collection();
    }

    public function setRemixers(Collection $remixers): self
    {
        $this->remixers = $remixers->filter(function ($remixer) {
            return $remixer instanceof Artist;
        });

        return $this;
    }

    public function addRemixer(Artist $remixer): self
    {
        if (! $this->remixers) {
            $this->remixers = new Collection();
        }

        $this->remixers->push($remixer);

        return $this;
    }
}
