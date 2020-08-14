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

    /**
     * @param PaginationActionEnum|string $action
     * @param int|null $amount
     * @return RequestInterface
     */
    public function paginate($action, int $amount = null): RequestInterface;

    public function isFirstPage(): bool;

    public function isLastPage(): bool;
}
