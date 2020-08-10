<?php

namespace Wimski\Beatport\Requests;

use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;

class RequestConfig
{
    /**
     * @var ResourceTypeEnum
     */
    protected $resourceType;

    /**
     * @var RequestTypeEnum
     */
    protected $requestType;

    /**
     * @var bool
     */
    protected $canHavePagination;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $queryParams;

    public function __construct(
        ResourceTypeEnum $resourceType,
        RequestTypeEnum $requestType,
        bool $canHavePagination,
        string $path,
        array $queryParams
    ) {

        $this->resourceType      = $resourceType;
        $this->requestType       = $requestType;
        $this->canHavePagination = $canHavePagination;
        $this->path              = $path;
        $this->queryParams       = $queryParams;
    }

    public function resourceType(): ResourceTypeEnum
    {
        return $this->resourceType;
    }

    public function requestType(): RequestTypeEnum
    {
        return $this->requestType;
    }

    public function canHavePagination(): bool
    {
        return $this->canHavePagination;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function queryParams(): array
    {
        return $this->queryParams;
    }
}
