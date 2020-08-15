<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Data\Key;
use Wimski\Beatport\Processors\Crawler;

class KeyResourceProcessor extends AbstractResourceProcessor
{
    protected function processIndex(Crawler $html): ?Collection
    {
        $keys = $html->filter('.filter-key-drop li')->each(function (Crawler $item) {
            $key = new Key();

            $label = $item->get('label');
            $title = trim($label->getNode(0)->firstChild->nodeValue);

            $key
                ->setId((int) $item->getAttr('input', 'name'))
                ->setTitle($title);

            return $key;
        });

        return collect($keys);
    }
}
