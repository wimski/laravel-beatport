<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Processors\Crawler;

class LabelResourceProcessor extends AbstractResourceProcessor
{
    protected function processView(Crawler $html): ?DataInterface
    {
        $anchor = $html->get('.interior-title a');
        $props  = $this->urlProcessor->processResourceAttributes($anchor->attr('href'));
        $props['title'] = $anchor->getText('h1');

        $label = new Label($props);
        $label->setArtwork($html->getAttr('.interior-top-artwork-parent a img', 'src'));

        return $label;
    }

    protected function processSearch(Crawler $html): ?Collection
    {
        $items = $html->filter('.bucket-items .bucket-item');

        if (! $items) {
            return null;
        }

        $labels = $items->each(function (Crawler $item) {
            $anchor = $item->get('a');
            $props  = $this->urlProcessor->processResourceAttributes($anchor->attr('href'));
            $props['title'] = $anchor->getText('.label-name');

            $label = new Label($props);
            $label->setArtwork($anchor->getAttr('.label-artwork', 'src'));

            return $label;
        });

        return collect($labels);
    }
}
