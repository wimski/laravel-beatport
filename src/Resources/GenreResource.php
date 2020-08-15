<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Builders\ViewRequestBuilder;
use Wimski\Beatport\Resources\Traits\CanIndex;
use Wimski\Beatport\Resources\Traits\CanRelate;

class GenreResource extends AbstractResource
{
    use CanIndex;
    use CanRelate;

    public function type(): ResourceTypeEnum
    {
        return ResourceTypeEnum::GENRE();
    }

    public function relationships(): Collection
    {
        return collect([
            ReleaseResource::class,
            TrackResource::class,
        ]);
    }

    public static function find(string $slug, int $id): RequestBuilderInterface
    {
        $resource = new static();

        $builder = resolve(ViewRequestBuilder::class, [
            'resource' => $resource,
        ]);

        return $builder->customPath("/tracks/all?genres={$id}");
    }

    public static function findByData(DataInterface $data): RequestBuilderInterface
    {
        return static::find($data->getSlug(), $data->getId());
    }
}
