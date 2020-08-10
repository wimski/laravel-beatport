<?php

namespace Wimski\Beatport\Contracts;

use Illuminate\Support\Collection;

interface RequestInterface
{
    /**
     * @return Collection<DataInterface>|DataInterface|null
     */
    public function data();

    public function paginate(string $action, int $amount = null): RequestInterface;
}
