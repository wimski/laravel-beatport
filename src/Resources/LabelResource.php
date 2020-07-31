<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Resources\Traits\CanView;
use Wimski\Beatport\Resources\Traits\CanSearch;

class LabelResource extends AbstractResource
{
    use CanView;
    use CanSearch;

    public function type(): string
    {
        return ResourceTypeEnum::LABEL;
    }

    protected function searchFilters(): Collection
    {
        return collect([
            ResourceFilter::make('genre')->multiple(),
            ResourceFilter::make('subgenre')->multiple(),
        ]);
    }
}
