<?php

namespace Wimski\Beatport\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;

abstract class AbstractResource implements ResourceInterface
{
    public function getFilter(RequestTypeEnum $requestType, string $name): ?RequestFilterInterface
    {
        switch ($requestType->getValue()) {
            case RequestTypeEnum::INDEX:
                $filters = $this->indexFilters();
                break;

            case RequestTypeEnum::RELATIONSHIP:
                $filters = $this->relationshipFilters();
                break;

            case RequestTypeEnum::QUERY:
                $filters = $this->searchFilters();
                break;

            default:
                $filters = new Collection();
        }

        return $filters->filter(function (RequestFilterInterface $filter) use ($name) {
            return $filter->name() === $name;
        })->first();
    }

    public function getSort(RequestTypeEnum $requestType, string $name): ?RequestSortInterface
    {
        switch ($requestType->getValue()) {
            case RequestTypeEnum::INDEX:
                $sorts = $this->indexSorts();
                break;

            case RequestTypeEnum::RELATIONSHIP:
                $sorts = $this->relationshipSorts();
                break;

            case RequestTypeEnum::QUERY:
                $sorts = $this->searchSorts();
                break;

            default:
                $sorts = new Collection();
        }

        return $sorts->filter(function (RequestSortInterface $sort) use ($name) {
            return $sort->name() === $name;
        })->first();
    }

    public function hasRelationship(string $relationship): bool
    {
        return $this->relationships()->filter(function (string $class) use ($relationship) {
            return $relationship === $class;
        })->isNotEmpty();
    }

    public function indexFilters(): Collection
    {
        return new Collection();
    }

    public function relationshipFilters(): Collection
    {
        return new Collection();
    }

    public function searchFilters(): Collection
    {
        return new Collection();
    }

    public function indexSorts(): Collection
    {
        return new Collection();
    }

    public function relationshipSorts(): Collection
    {
        return new Collection();
    }

    public function searchSorts(): Collection
    {
        return new Collection();
    }

    public function relationships(): Collection
    {
        return new Collection();
    }
}
