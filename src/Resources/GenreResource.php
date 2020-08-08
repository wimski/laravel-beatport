<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Resources\Traits\CanView;
use Wimski\Beatport\Resources\Traits\HasRelationships;

class GenreResource extends AbstractResource
{
    use CanView;
    use HasRelationships;

    public function type(): string
    {
        return ResourceTypeEnum::GENRE;
    }

    protected function relationships(): Collection
    {
        return collect([
            ReleaseResource::class,
            TrackResource::class,
        ]);
    }
}
