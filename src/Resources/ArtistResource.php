<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Resources\Traits\CanView;
use Wimski\Beatport\Resources\Traits\CanSearch;

class ArtistResource extends AbstractResource
{
    use CanView;
    use CanSearch;

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
}
