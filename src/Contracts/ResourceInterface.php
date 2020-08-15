<?php

namespace Wimski\Beatport\Contracts;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;

interface ResourceInterface
{
    public function type(): ResourceTypeEnum;

    public function getFilter(RequestTypeEnum $requestType, string $name): ?RequestFilterInterface;

    public function getSort(RequestTypeEnum $requestType, string $name): ?RequestSortInterface;

    public function hasRelationship(string $relationship): bool;

    /**
     * @return Collection<RequestFilterInterface>
     */
    public function indexFilters(): Collection;

    /**
     * @return Collection<RequestFilterInterface>
     */
    public function relationshipFilters(): Collection;

    /**
     * @return Collection<RequestFilterInterface>
     */
    public function searchFilters(): Collection;

    /**
     * @return Collection<RequestSortInterface>
     */
    public function indexSorts(): Collection;

    /**
     * @return Collection<RequestSortInterface>
     */
    public function relationshipSorts(): Collection;

    /**
     * @return Collection<RequestSortInterface>
     */
    public function searchSorts(): Collection;

    /**
     * @return Collection<string>
     */
    public function relationships(): Collection;
}
