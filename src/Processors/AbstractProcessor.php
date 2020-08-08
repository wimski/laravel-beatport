<?php

namespace Wimski\Beatport\Processors;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ProcessorInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Request;

abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @var Crawler
     */
    protected $crawler;

    public function process(RequestTypeEnum $requestType, string $html)
    {
        $this->crawler = new Crawler($html);

        if ($requestType->equals(RequestTypeEnum::VIEW)) {
            return $this->processSingle();
        }

        return $this->processMultiple();
    }

    protected function getContentRoot(): Crawler
    {
        return $this->crawler->get('#pjax-target');
    }

    protected function processSingle(): ?DataInterface
    {
        return null;
    }

    /**
     * @return Collection<DataInterface>|null
     */
    protected function processMultiple(): ?Collection
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
        $regex .= '([a-z0-9\-\%]+)\/?';
        $regex .= '(\d+)\/?';

        return $regex;
    }
}
