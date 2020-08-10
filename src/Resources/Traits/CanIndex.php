<?php

namespace Wimski\Beatport\Resources\Traits;

use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Requests\Builders\IndexRequestBuilder;

trait CanIndex
{
    use ResourceInterfaceTrait;

    public static function all(): RequestBuilderInterface
    {
        static::checkIfResourceInterface();

        /** @var ResourceInterface $resource */
        $resource = new static();

        return resolve(IndexRequestBuilder::class, [
            'resource' => $resource,
        ]);
    }
}
