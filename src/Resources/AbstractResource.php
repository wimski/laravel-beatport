<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;

abstract class AbstractResource implements ResourceInterface
{
    public function typePlural(): string
    {
        return Str::plural($this->type());
    }

    public function getFilter(string $requestType, string $name): ?RequestFilterInterface
    {
        switch ($requestType) {
            case RequestTypeEnum::INDEX:
                $filters = $this->indexFilters();
                break;

            case RequestTypeEnum::SEARCH:
                $filters = $this->searchFilters();
                break;

            default:
                $filters = new Collection();
        }

        return $filters->filter(function (RequestFilterInterface $filter) use ($name) {
            return $filter->name() === $name;
        })->first();
    }

    public function getSort(string $requestType, string $name): ?RequestSortInterface
    {
        switch ($requestType) {
            case RequestTypeEnum::INDEX:
                $sorts = $this->indexSorts();
                break;

            case RequestTypeEnum::SEARCH:
                $sorts = $this->searchSorts();
                break;

            default:
                $sorts = new Collection();
        }

        return $sorts->filter(function (RequestSortInterface $sort) use ($name) {
            return $sort->name() === $name;
        })->first();
    }

    /**
     * @return Collection<RequestFilterInterface>
     */
    protected function indexFilters(): Collection
    {
        return new Collection();
    }

    /**
     * @return Collection<RequestFilterInterface>
     */
    protected function searchFilters(): Collection
    {
        return new Collection();
    }

    /**
     * @return Collection<RequestSortInterface>
     */
    protected function indexSorts(): Collection
    {
        return new Collection();
    }

    /**
     * @return Collection<RequestSortInterface>
     */
    protected function searchSorts(): Collection
    {
        return new Collection();
    }
}
