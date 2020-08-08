<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Resources\Traits\CanView;
use Wimski\Beatport\Resources\Traits\CanSearch;
use Wimski\Beatport\Resources\Traits\HasRelationships;

class ArtistResource extends AbstractResource
{
    use CanView;
    use CanSearch;
    use HasRelationships;

    public function type(): string
    {
        return ResourceTypeEnum::ARTIST;
    }

    protected function searchFilters(): Collection
    {
        return collect([
            ResourceFilter::make('genre')->multiple(),
            ResourceFilter::make('subgenre')->multiple(),
        ]);
    }

    protected function relationships(): Collection
    {
        return collect([
            ReleaseResource::class,
            TrackResource::class,
        ]);
    }
}
