<?php

namespace Wimski\Beatport\Requests\Filters;

use Wimski\Beatport\Contracts\RequestFilterInterface;

abstract class AbstractFilter implements RequestFilterInterface
{
    /**
     * @var string
     */
    protected $name;

    public function name(): string
    {
        return $this->name;
    }
}
