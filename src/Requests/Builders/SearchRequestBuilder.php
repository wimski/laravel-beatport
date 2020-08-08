<?php

namespace Wimski\Beatport\Requests\Builders;

use Wimski\Beatport\Enums\RequestTypeEnum;

class SearchRequestBuilder extends AbstractRequestBuilder
{
    /**
     * @var string
     */
    protected $query;

    public function type(): RequestTypeEnum
    {
        return RequestTypeEnum::QUERY();
    }

    public function query(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function path(): string
    {
        return '/search/' . $this->resource->type()->getValuePlural();
    }

    public function queryParams(): array
    {
        return array_merge(parent::queryParams(), [
            'q' => $this->query,
        ]);
    }
}
