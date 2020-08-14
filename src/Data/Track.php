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
     * @var string
     */
    protected $waveform;

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

    public function setRemix(string $remix = null): self
    {
        $this->remix = $remix;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int|string|null $length
     * @return $this
     */
    public function setLength($length = null): self
    {
        $this->length = $this->parseDuration($length);

        return $this;
    }

    public function getBpm(): ?int
    {
        return $this->bpm;
    }

    public function setBpm(int $bpm = null): self
    {
        $this->bpm = $bpm;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key = null): self
    {
        $this->key = $key;

        return $this;
    }

    public function getWaveform(): ?string
    {
        return $this->waveform;
    }

    public function setWaveform(string $waveform = null): self
    {
        $this->waveform = $waveform;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(Genre $genre = null): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getSubGenre(): ?SubGenre
    {
        return $this->subGenre;
    }

    public function setSubGenre(SubGenre $subGenre = null): self
    {
        $this->subGenre = $subGenre;

        return $this;
    }

    public function getRelease(): ?Release
    {
        return $this->release;
    }

    public function setRelease(Release $release = null): self
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
