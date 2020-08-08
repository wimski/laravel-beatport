<?php

namespace Wimski\Beatport\Contracts;

interface ResourceProcessorFactoryInterface
{
    public function make(ResourceInterface $resource): ResourceProcessorInterface;
}
