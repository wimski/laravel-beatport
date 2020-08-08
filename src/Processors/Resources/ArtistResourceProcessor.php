<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Artist;
use Wimski\Beatport\Processors\Crawler;

class ArtistResourceProcessor extends AbstractResourceProcessor
{
    protected function processView(Crawler $html): ?DataInterface
    {
        $artist = new Artist($this->processAnchor($html->get('.interior-title a')));
        $artist->setArtwork($html->getAttr('.interior-artist-artwork', 'src'));

        return $artist;
    }

    protected function processIndex(Crawler $html): ?Collection
    {
        return $this->processMultiple($html);
    }

    protected function processSearch(Crawler $html): ?Collection
    {
        return $this->processMultiple($html);
    }

    protected function processMultiple(Crawler $html): ?Collection
    {
        $items = $html->filter('.bucket-items .bucket-item');

        if (! $items) {
            return null;
        }

        $artists = $items->each(function (Crawler $item) {
            $anchor = $item->get('a');
            $props  = $this->urlProcessor->process($anchor->attr('href'));
            $props['title'] = $anchor->getText('.artist-name');

            $artist = new Artist($props);
            $artist->setArtwork($anchor->getAttr('img', 'src'));

            return $artist;
        });

        return collect($artists);
    }
}
