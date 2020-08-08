<?php

namespace Wimski\Beatport\Processors;

use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

class Crawler extends SymfonyCrawler
{
    /**
     * @param string $selector
     * @return static
     */
    public function get(string $selector)
    {
        return $this->filter($selector)->first();
    }

    public function getText(string $selector): ?string
    {
        $node = $this->get($selector);

        if (! $node->count()) {
            return null;
        }

        return $node->text();
    }

    public function getAttr(string $selector, string $attr): ?string
    {
        $node = $this->get($selector);

        if (! $node->count()) {
            return null;
        }

        return $node->attr($attr);
    }
}
