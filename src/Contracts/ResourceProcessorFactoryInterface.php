<?php

namespace Wimski\Beatport\Contracts;

use Wimski\Beatport\Exceptions\InvalidResourceException;

interface ResourceProcessorFactoryInterface
{
    /**
     * @param ResourceInterface $resource
     * @return ResourceProcessorInterface
     * @throws InvalidResourceException
     */
    public function make(ResourceInterface $resource): ResourceProcessorInterface;
}
