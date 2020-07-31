<?php

namespace Wimski\Beatport\Contracts;

use Wimski\Beatport\Exceptions\InvalidFilterInputException;

interface RequestFilterInterface
{
    public function name(): string;

    public function queryParams(): array;

    /**
     * @param $input
     * @return RequestFilterInterface
     * @throws InvalidFilterInputException
     */
    public function input($input): RequestFilterInterface;
}
