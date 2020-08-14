<?php

namespace Wimski\Beatport\Contracts;

use Wimski\Beatport\Enums\RequestPageSizeEnum;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Exceptions\InvalidFilterException;
use Wimski\Beatport\Exceptions\InvalidSortException;

interface RequestBuilderInterface
{
    public function type(): RequestTypeEnum;

    public function resource(): ResourceInterface;

    public function canHavePagination(): bool;

    /**
     * @param string $name
     * @param $value
     * @return RequestBuilderInterface
     * @throws InvalidFilterException
     */
    public function filter(string $name, $value): RequestBuilderInterface;

    /**
     * @param string $name
     * @param string $direction
     * @return RequestBuilderInterface
     * @throws InvalidSortException
     */
    public function sort(string $name, string $direction = 'asc'): RequestBuilderInterface;

    /**
     * @param RequestPageSizeEnum|int $pageSize
     * @return RequestBuilderInterface
     */
    public function pageSize($pageSize): RequestBuilderInterface;

    public function path(): string;

    public function queryParams(): array;

    public function get(): RequestInterface;
}
