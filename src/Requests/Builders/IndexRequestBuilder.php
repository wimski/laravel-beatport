<?php

namespace Wimski\Beatport\Requests\Builders;

use Wimski\Beatport\Enums\RequestTypeEnum;

class IndexRequestBuilder extends AbstractRequestBuilder
{
    public function type(): RequestTypeEnum
    {
        return RequestTypeEnum::INDEX();
    }

    public function path(): string
    {
        return '/' . $this->resource->type()->getValuePlural() . '/all';
    }
}
