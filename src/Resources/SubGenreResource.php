<?php

namespace Wimski\Beatport\Resources;

use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Builders\IndexRequestBuilder;
use Wimski\Beatport\Resources\Traits\CanView;

class SubGenreResource extends AbstractResource
{
    use CanView;

    public function type(): ResourceTypeEnum
    {
        return ResourceTypeEnum::SUB_GENRE();
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
