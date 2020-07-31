<?php

namespace Wimski\Beatport\Processors;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ProcessorInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Request;

abstract class AbstractProcessor implements ProcessorInterface
{
    public function process(string $html, bool $multipleResources)
    {
        $crawler = new Crawler($html);

        $meta = $crawler->filter('head meta');
        $root = $crawler->get('#pjax-target');

        if ($multipleResources) {
            return $this->processMultiple($meta, $root);
        }

        return $this->processSingle($meta, $root);
    }

    protected function processSingle(Crawler $meta, Crawler $root): ?DataInterface
    {
        return null;
    }

    /**
     * @param Crawler $meta
     * @param Crawler $root
     * @return Collection<DataInterface>|null
     */
    protected function processMultiple(Crawler $meta, Crawler $root): ?Collection
    {
        return null;
    }

    protected function parseUrl(string $url): array
    {
        preg_match("/^{$this->getRegex()}/", $url, $matches);

        return [
            'type' => new ResourceTypeEnum($matches[1]),
            'slug' => $matches[2],
            'id'   => $matches[3],
        ];
    }

    protected function getRegex(): string
    {
        $url = Request::URL;

        $regex =  '(?:' . preg_quote($url, '/') . ')?\/?';
        $regex .= '(' . implode('|', ResourceTypeEnum::values()) . ')\/?';
        $regex .= '([a-z0-9\-]+)\/?';
        $regex .= '(\d+)\/?';

        return $regex;
    }
}
