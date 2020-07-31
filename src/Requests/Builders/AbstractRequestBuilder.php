<?php

namespace Wimski\Beatport\Requests\Builders;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\RequestPageSizeEnum;
use Wimski\Beatport\Exceptions\InvalidFilterException;
use Wimski\Beatport\Exceptions\InvalidPageSizeException;
use Wimski\Beatport\Exceptions\InvalidSortException;

abstract class AbstractRequestBuilder implements RequestBuilderInterface
{
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
     * @var int
     */
    protected $pageSize;

    /**
     * @var bool
     */
    protected $multipleResults;

    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;

        $this->filters = new Collection();
    }

    public function resource(): ResourceInterface
    {
        return $this->resource;
    }

    public function multipleResults(): bool
    {
        return $this->multipleResults === true;
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

    public function pageSize(int $pageSize): RequestBuilderInterface
    {
        if (! RequestPageSizeEnum::isValid($pageSize)) {
            throw new InvalidPageSizeException('');
        }

        $this->pageSize = $pageSize;

        return $this;
    }

    public function queryParams(): array
    {
        $params = [];

        if ($this->pageSize) {
            $params['per-page'] = $this->pageSize;
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
        return resolve(RequestInterface::class, [
            'builder' => $this,
        ]);
    }
}
