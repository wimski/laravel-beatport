<?php

namespace Wimski\Beatport\Requests\Sorts;

use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Exceptions\InvalidSortDirectionException;

class Sort implements RequestSortInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $direction;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function queryParams(): array
    {
        $value = $this->direction ?? 'asc';

        return [
            'sort' => $this->name . '-' . $value,
        ];
    }

    public function direction(string $direction): RequestSortInterface
    {
        if (! in_array($direction, ['asc', 'desc'])) {
            throw new InvalidSortDirectionException("The sort direction must be 'asc' or 'desc'");
        }

        $this->direction = $direction;

        return $this;
    }
}
