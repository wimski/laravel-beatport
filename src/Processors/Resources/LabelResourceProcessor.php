<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Processors\Crawler;

class LabelResourceProcessor extends AbstractResourceProcessor
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

        $label = new Label($props);
        $label->setArtwork($interior->getAttr('.interior-top-artwork-parent a img', 'src'));

        return $label;
    }

    protected function processMultiple(): ?Collection
    {
        $items = $this->getContentRoot()->filter('.bucket-items .bucket-item');

        if (! $items) {
            return null;
        }

        $labels = $items->each(function (Crawler $item) {
            $anchor = $item->get('a');
            $props  = $this->urlProcessor->process($anchor->attr('href'));
            $props['title'] = $anchor->getText('.label-name');

            $label = new Label($props);
            $label->setArtwork($anchor->getAttr('.label-artwork', 'src'));

            return $label;
        });

        return collect($labels);
    }
}
