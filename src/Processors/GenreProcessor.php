<?php

namespace Wimski\Beatport\Processors;

use Illuminate\Support\Collection;
use Wimski\Beatport\Data\Genre;

class GenreProcessor extends AbstractProcessor
{
    protected function processMultiple(): ?Collection
    {
        $genres = $this->crawler->filter('.genre-drop-list__genre')->each(function (Crawler $item) {
            $props = $this->parseUrl($item->attr('href'));
            $props['title'] = $item->text();

            return new Genre($props);
        });

        return collect($genres);
    }
}
