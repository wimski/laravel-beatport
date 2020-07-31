<?php

namespace Wimski\Beatport\Data;

use Wimski\Beatport\Data\Traits\HasArtwork;
use Wimski\Beatport\Data\Traits\HasReleases;
use Wimski\Beatport\Data\Traits\HasTracks;

class Artist extends AbstractData
{
    use HasArtwork;
    use HasReleases;
    use HasTracks;
}
