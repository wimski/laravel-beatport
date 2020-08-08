<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Resources\Traits\CanView;
use Wimski\Beatport\Resources\Traits\CanSearch;
use Wimski\Beatport\Resources\Traits\CanRelate;

class LabelResource extends AbstractResource
{
    use CanRelate;
    use CanSearch;
    use CanView;

    public function type(): ResourceTypeEnum
    {
        return ResourceTypeEnum::LABEL();
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
