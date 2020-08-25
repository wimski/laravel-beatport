<?php

namespace Wimski\Beatport\Contracts;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\PaginationActionEnum;
use Wimski\Beatport\Requests\RequestConfig;

interface RequestInterface
{
    public function response(): ?string;

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

    public function hasPagination(): bool;

    public function currentPage(): ?int;

    public function totalPages(): ?int;

    public function startWithConfig(RequestConfig $requestConfig): RequestInterface;

    public function toArray(): array;

    public function fromArray(array $data): RequestInterface;
}
