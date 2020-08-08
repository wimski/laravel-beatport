<?php

namespace Wimski\Beatport\Processors;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ProcessorInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;

abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @var UrlProcessor
     */
    protected $urlProcessor;

    /**
     * @var Crawler
     */
    protected $html;

    public function __construct(UrlProcessor $urlProcessor)
    {
        $this->urlProcessor = $urlProcessor;
    }

    public function process(RequestTypeEnum $requestType, string $html)
    {
        $this->html = new Crawler($html);

        if ($requestType->equals(RequestTypeEnum::VIEW)) {
            return $this->processSingle();
        }

        return $this->processMultiple();
    }

    protected function getContentRoot(): Crawler
    {
        return $this->html->get('#pjax-target');
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
}
