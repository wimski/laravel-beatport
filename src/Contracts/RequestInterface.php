<?php

namespace Wimski\Beatport\Contracts;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\PaginationActionEnum;

interface RequestInterface
{
    /**
     * @return Collection<DataInterface>|DataInterface|null
     */
    public function data();

    public function paginate(PaginationActionEnum $action, int $amount = null): RequestInterface;
}
