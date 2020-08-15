<?php

namespace Wimski\Beatport\Resources\Traits;

use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Requests\Builders\SearchRequestBuilder;

trait CanSearch
{
    use ResourceInterfaceTrait;

    public static function search(string $query): SearchRequestBuilder
    {
        static::checkIfResourceInterface();

        /** @var ResourceInterface $resource */
        $resource = new static();

        $builder = resolve(SearchRequestBuilder::class, [
            'resource' => $resource,
        ]);

        return $builder->query($query);
    }
}
