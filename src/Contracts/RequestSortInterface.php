<?php

namespace Wimski\Beatport\Contracts;

use Wimski\Beatport\Exceptions\InvalidSortDirectionException;

interface RequestSortInterface
{
    public function name(): string;

    public function queryParams(): array;

    /**
     * @param string $direction
     * @return RequestSortInterface
     * @throws InvalidSortDirectionException
     */
    public function direction(string $direction): RequestSortInterface;
}
