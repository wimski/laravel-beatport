<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Resources\Traits\CanIndex;
use Wimski\Beatport\Resources\Traits\CanView;
use Wimski\Beatport\Resources\Traits\CanSearch;

class ReleaseResource extends AbstractResource
{
    use CanIndex;
    use CanView;
    use CanSearch;

    public function type(): string
    {
        return ResourceTypeEnum::RELEASE;
    }

    protected function searchFilters(): Collection
    {
        return collect([
            ResourceFilter::make('artists')->multiple(),
            ResourceFilter::make('genres')->multiple(),
            ResourceFilter::make('label'),
            ResourceFilter::make('subgenre')->multiple(),
        ]);
    }
}
