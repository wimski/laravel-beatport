<?php

namespace Wimski\Beatport\Requests\Builders;

use Wimski\Beatport\Enums\RequestTypeEnum;

class IndexRequestBuilder extends AbstractRequestBuilder
{
    /**
     * @var bool
     */
    protected $multipleResults = true;

    public function type(): string
    {
        return RequestTypeEnum::INDEX;
    }

    public function path(): string
    {
        return '/' . $this->resource->typePlural() . '/all';
    }
}
