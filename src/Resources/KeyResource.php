<?php

namespace Wimski\Beatport\Resources;

use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Builders\IndexRequestBuilder;

class KeyResource extends AbstractResource
{
    public function type(): ResourceTypeEnum
    {
        return ResourceTypeEnum::KEY();
    }

    public static function all(): RequestBuilderInterface
    {
        $resource = new static();

        $builder = resolve(IndexRequestBuilder::class, [
            'resource' => $resource,
        ]);

        $builder->customPath('/tracks/all');

        return $builder;
    }
}
