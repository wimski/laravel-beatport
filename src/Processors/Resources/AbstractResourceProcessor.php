<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ResourceProcessorInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Processors\Crawler;
use Wimski\Beatport\Processors\UrlProcessor;

abstract class AbstractResourceProcessor implements ResourceProcessorInterface
{
    /**
     * @var UrlProcessor
     */
    protected $urlProcessor;

    public function __construct(UrlProcessor $urlProcessor)
    {
        $this->urlProcessor = $urlProcessor;
    }

    public function process(RequestTypeEnum $requestType, string $html)
    {
        switch ($requestType->getValue()) {
            case RequestTypeEnum::INDEX:
                return $this->processIndex(new Crawler($html));

            case RequestTypeEnum::RELATIONSHIP:
                return $this->processRelationship(new Crawler($html));

            case RequestTypeEnum::QUERY:
                return $this->processSearch(new Crawler($html));

            case RequestTypeEnum::VIEW:
                return $this->processView(new Crawler($html));

            default:
                // omitted on purpose
        }
    }

    protected function processAnchor(Crawler $anchor): array
    {
        $props = $this->urlProcessor->process($anchor->attr('href'));
        $props['title'] = $anchor->text();

        return $props;
    }

    /**
     * @param Crawler $html
     * @return Collection<DataInterface>|null
     */
    protected function processIndex(Crawler $html): ?Collection
    {
        return null;
    }

    /**
     * @param Crawler $html
     * @return Collection<DataInterface>|null
     */
    protected function processRelationship(Crawler $html): ?Collection
    {
        return null;
    }

    /**
     * @param Crawler $html
     * @return Collection<DataInterface>|null
     */
    protected function processSearch(Crawler $html): ?Collection
    {
        return null;
    }

    protected function processView(Crawler $html): ?DataInterface
    {
        return null;
    }
}
