<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Resources\Traits\CanIndex;
use Wimski\Beatport\Resources\Traits\CanRelate;

class GenreResource extends AbstractResource
{
    use CanRelate;
    use CanIndex;

    public function type(): ResourceTypeEnum
    {
        return ResourceTypeEnum::GENRE();
    }

    protected function relationships(): Collection
    {
        return collect([
            ReleaseResource::class,
            TrackResource::class,
        ]);
    }
}
