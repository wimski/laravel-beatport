<?php

namespace Wimski\Beatport\Contracts;

use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Exceptions\InvalidFilterException;
use Wimski\Beatport\Exceptions\InvalidPageSizeException;
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
     * @param int $pageSize
     * @return RequestBuilderInterface
     * @throws InvalidPageSizeException
     */
    public function pageSize(int $pageSize): RequestBuilderInterface;

    public function path(): string;

    public function queryParams(): array;

    public function get(): RequestInterface;
}
