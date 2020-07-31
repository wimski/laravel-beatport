<?php

namespace Wimski\Beatport\Requests\Filters;

abstract class AbstractAttributeFilter extends AbstractFilter
{
    public static function make(): self
    {
        return new static();
    }
}
