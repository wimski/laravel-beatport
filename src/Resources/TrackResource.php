<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\BpmFilter;
use Wimski\Beatport\Requests\Filters\DateFilter;
use Wimski\Beatport\Requests\Filters\PreorderFilter;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Requests\Filters\TypeFilter;
use Wimski\Beatport\Requests\Sorts\Sort;
use Wimski\Beatport\Resources\Traits\CanIndex;
use Wimski\Beatport\Resources\Traits\CanView;
use Wimski\Beatport\Resources\Traits\CanSearch;

class TrackResource extends AbstractResource
{
    use CanIndex;
    use CanSearch;
    use CanView;

    public function type(): ResourceTypeEnum
    {
        return ResourceTypeEnum::TRACK();
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
            BpmFilter::make(),
            DateFilter::make(),
            TypeFilter::make(),
        ]);
    }

    public function searchFilters(): Collection
    {
        return collect([
            ResourceFilter::make('artists')->multiple(),
            ResourceFilter::make('genres'),
            ResourceFilter::make('key'),
            ResourceFilter::make('label'),
            ResourceFilter::make('subgenre'),
        ]);
    }

    public function indexSorts(): Collection
    {
        return $this->sorts();
    }

    public function relationshipSorts(): Collection
    {
        return $this->sorts();
    }

    public function searchSorts(): Collection
    {
        return $this->sorts();
    }

    /**
     * @return Collection<RequestSortInterface>
     */
    public function sorts(): Collection
    {
        return collect([
            Sort::make('genre'),
            Sort::make('label'),
            Sort::make('release'),
            Sort::make('title'),
        ]);
    }
}
