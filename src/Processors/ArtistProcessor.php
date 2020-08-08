<?php

namespace Wimski\Beatport\Processors;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Artist;

class ArtistProcessor extends AbstractProcessor
{
    protected function processSingle(): ?DataInterface
    {
        $interior = $this->getContentRoot()->get('.interior');
        if (! $interior) {
            return null;
        }

        $anchor = $interior->get('.interior-title a');
        $props  = $this->urlProcessor->process($anchor->attr('href'));
        $props['title'] = $anchor->getText('h1');

        $artist = new Artist($props);
        $artist->setArtwork($interior->getAttr('.interior-artist-artwork', 'src'));

        return $artist;
    }

    protected function processMultiple(): ?Collection
    {
        $items = $this->getContentRoot()->filter('.bucket-items .bucket-item');

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
