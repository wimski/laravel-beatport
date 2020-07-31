<?php

namespace Wimski\Beatport\Data;

use Wimski\Beatport\Data\Traits\HasArtists;
use Wimski\Beatport\Data\Traits\HasArtwork;
use Wimski\Beatport\Data\Traits\HasLabel;
use Wimski\Beatport\Data\Traits\HasReleased;
use Wimski\Beatport\Data\Traits\HasTracks;
use Wimski\Beatport\Traits\ParsesDate;

class Release extends AbstractData
{
    use HasArtists;
    use HasArtwork;
    use HasLabel;
    use HasReleased;
    use HasTracks;
    use ParsesDate;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $catalog;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCatalog(): ?string
    {
        return $this->catalog;
    }

    public function setCatalog(string $catalog): self
    {
        $this->catalog = $catalog;

        return $this;
    }
}
