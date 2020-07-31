<?php

namespace Wimski\Beatport\Data;

use Wimski\Beatport\Data\Traits\HasReleases;
use Wimski\Beatport\Data\Traits\HasTracks;

class Genre extends AbstractData
{
    use HasReleases;
    use HasTracks;
}
