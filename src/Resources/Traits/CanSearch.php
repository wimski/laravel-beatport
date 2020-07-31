<?php

namespace Wimski\Beatport\Resources\Traits;

use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Requests\Builders\SearchRequestBuilder;

trait CanSearch
{
    use ResourceInterfaceTrait;

    public static function search(string $query): RequestBuilderInterface
    {
        static::checkIfResourceInterface();

        /** @var ResourceInterface $resource */
        $resource = new static();

        return (new SearchRequestBuilder($resource))->query($query);
    }
}
