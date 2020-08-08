<?php

namespace Wimski\Beatport\Contracts;

use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;

interface ResourceInterface
{
    public function type(): ResourceTypeEnum;

    public function getFilter(RequestTypeEnum $requestType, string $name): ?RequestFilterInterface;

    public function getSort(RequestTypeEnum $requestType, string $name): ?RequestSortInterface;

    public function hasRelationship(string $relationship): bool;
}
