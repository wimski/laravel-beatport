<?php

namespace Wimski\Beatport\Contracts;

interface ProcessorFactoryInterface
{
    public function make(ResourceInterface $resource): ProcessorInterface;
}
