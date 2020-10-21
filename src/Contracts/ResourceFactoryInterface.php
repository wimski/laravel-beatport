<?php

namespace Wimski\Beatport\Contracts;

use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Exceptions\InvalidResourceException;

interface ResourceFactoryInterface
{
    /**
     * @param ResourceTypeEnum|string $value
     * @return ResourceInterface
     * @throws InvalidResourceException
     */
    public function make($value): ResourceInterface;
}
