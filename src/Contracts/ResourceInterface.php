<?php

namespace Wimski\Beatport\Contracts;

interface ResourceInterface
{
    public function type(): string;

    public function typePlural(): string;

    public function getFilter(string $requestType, string $name): ?RequestFilterInterface;

    public function getSort(string $requestType, string $name): ?RequestSortInterface;

    public function hasRelationship(string $relationship): bool;
}
