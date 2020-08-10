<?php

namespace Wimski\Beatport\Contracts;

use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Exceptions\InvalidResourceException;

interface ResourceProcessorFactoryInterface
{
    /**
     * @param ResourceTypeEnum $type
     * @return ResourceProcessorInterface
     * @throws InvalidResourceException
     */
    public function make(ResourceTypeEnum $type): ResourceProcessorInterface;
}
