<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\DateFilter;
use Wimski\Beatport\Requests\Filters\PreorderFilter;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Requests\Filters\TypeFilter;
use Wimski\Beatport\Requests\Sorts\Sort;
use Wimski\Beatport\Resources\Traits\CanIndex;
use Wimski\Beatport\Resources\Traits\CanView;
use Wimski\Beatport\Resources\Traits\CanSearch;

class ReleaseResource extends AbstractResource
{
    use CanIndex;
    use CanSearch;
    use CanView;

    public function type(): ResourceTypeEnum
    {
        return ResourceTypeEnum::RELEASE();
    }

    public function indexFilters(): Collection
    {
        return $this->relationshipFilters()->merge([
            PreorderFilter::make(),
        ]);
    }

    public function relationshipFilters(): Collection
    {
        return $this->searchFilters()->merge([
            DateFilter::make(),
            TypeFilter::make(),
        ]);
    }

    public function searchFilters(): Collection
    {
        return collect([
            ResourceFilter::make('artists')->multiple(),
            ResourceFilter::make('genres')->multiple(),
            ResourceFilter::make('label'),
            ResourceFilter::make('subgenre')->multiple(),
        ]);
    }

    public function indexSorts(): Collection
    {
        return collect([
            Sort::make('label'),
            Sort::make('release'),
            Sort::make('title'),
        ]);
    }
}
