<?php

namespace Wimski\Beatport\Resources\Traits;

use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Requests\Builders\ViewRequestBuilder;

trait CanView
{
    use ResourceInterfaceTrait;

    public static function find(string $slug, int $id): ViewRequestBuilder
    {
        static::checkIfResourceInterface();

        /** @var ResourceInterface $resource */
        $resource = new static();

        $builder = resolve(ViewRequestBuilder::class, [
            'resource' => $resource,
        ]);

        return $builder
            ->slug($slug)
            ->id($id);
    }

    public static function findByData(DataInterface $data): ViewRequestBuilder
    {
        return static::find($data->getSlug(), $data->getId());
    }
}
