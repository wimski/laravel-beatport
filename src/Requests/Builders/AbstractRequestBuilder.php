<?php

namespace Wimski\Beatport\Requests\Builders;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\RequestPageSizeEnum;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Exceptions\InvalidFilterException;
use Wimski\Beatport\Exceptions\InvalidSortException;
use Wimski\Beatport\Requests\RequestConfig;

abstract class AbstractRequestBuilder implements RequestBuilderInterface
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * @var ResourceInterface
     */
    protected $resource;

    /**
     * @var Collection<RequestFilterInterface>
     */
    protected $filters;

    /**
     * @var RequestSortInterface
     */
    protected $sort;

    /**
     * @var RequestPageSizeEnum
     */
    protected $pageSize;

    public function __construct(Container $app, ResourceInterface $resource)
    {
        $this->app      = $app;
        $this->resource = $resource;

        $this->filters  = new Collection();
        $this->pageSize = RequestPageSizeEnum::PAGE_SIZE_25();
    }

    public function resource(): ResourceInterface
    {
        return $this->resource;
    }

    public function canHavePagination(): bool
    {
        return in_array($this->type()->getValue(), [
            RequestTypeEnum::INDEX,
            RequestTypeEnum::RELATIONSHIP,
            RequestTypeEnum::QUERY,
        ]);
    }

    public function filter(string $name, $value): RequestBuilderInterface
    {
        $filter = $this->resource->getFilter($this->type(), $name);

        if (! $filter) {
            throw new InvalidFilterException("Filter {$name} is not supported for " . get_class($this->resource));
        }

        $filter->input($value);

        $this->filters = $this->filters->filter(function (RequestFilterInterface $filter) use ($name) {
            return $filter->name() !== $name;
        });

        $this->filters->push($filter);

        return $this;
    }

    public function sort(string $name, string $direction = 'asc'): RequestBuilderInterface
    {
        $sort = $this->resource->getSort($this->type(), $name);

        if (! $sort) {
            throw new InvalidSortException("Sort {$name} is not supported for " . get_class($this->resource));
        }

        $sort->direction($direction);

        $this->sort = $sort;

        return $this;
    }

    public function pageSize(RequestPageSizeEnum $pageSize): RequestBuilderInterface
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    public function queryParams(): array
    {
        $params = [];

        if ($this->canHavePagination()) {
            $params['per-page'] = $this->pageSize->getValue();
        }

        /** @var RequestFilterInterface $filter */
        foreach ($this->filters as $filter) {
            $params = array_merge($params, $filter->queryParams());
        }

        if ($this->sort) {
            $params = array_merge($params, $this->sort->queryParams());
        }

        return $params;
    }

    public function get(): RequestInterface
    {
        return $this->app->make(RequestInterface::class, [
            'config' => new RequestConfig(
                $this->resource->type(),
                $this->type(),
                $this->canHavePagination(),
                $this->path(),
                $this->queryParams(),
            ),
        ]);
    }
}
