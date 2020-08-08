<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Resources\Traits\CanIndex;
use Wimski\Beatport\Resources\Traits\HasRelationships;

class GenreResource extends AbstractResource
{
    use CanIndex;
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
