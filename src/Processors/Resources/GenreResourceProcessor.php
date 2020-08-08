<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Processors\Crawler;

class GenreResourceProcessor extends AbstractResourceProcessor
{
    protected function processIndex(Crawler $html): ?Collection
    {
        $genres = $html->filter('.genre-drop-list__genre')->each(function (Crawler $item) {
            return new Genre($this->processAnchor($item));
        });

        return collect($genres);
    }
}
