<?php

namespace Wimski\Beatport\Resources\Traits;

use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Requests\Builders\ViewRequestBuilder;

trait CanView
{
    use ResourceInterfaceTrait;

    public static function find(string $slug, int $id)
    {
        static::checkIfResourceInterface();

        /** @var ResourceInterface $resource */
        $resource = new static();

        return (new ViewRequestBuilder($resource))
            ->slug($slug)
            ->id($id);
    }
}
