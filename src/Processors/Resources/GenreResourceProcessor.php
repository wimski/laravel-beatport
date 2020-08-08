<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Processors\Crawler;

class GenreResourceProcessor extends AbstractResourceProcessor
{
    protected function processMultiple(): ?Collection
    {
        $genres = $this->html->filter('.genre-drop-list__genre')->each(function (Crawler $item) {
            $props = $this->urlProcessor->process($item->attr('href'));
            $props['title'] = $item->text();

            return new Genre($props);
        });

        return collect($genres);
    }
}
