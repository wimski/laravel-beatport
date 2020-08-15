<?php

namespace Wimski\Beatport\Requests\Filters;

use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Exceptions\InvalidFilterInputException;

class ResourceFilter extends AbstractFilter
{
    /**
     * @var int[]
     */
    protected $values;

    /**
     * @var bool
     */
    protected $supportsMultipleValues = false;

    public function __construct(string $name)
    {
        $this->name   = $name;
        $this->values = [];
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function key(): string
    {
        return $this->name;
    }

    public function queryParams(): array
    {
        if (empty($this->values)) {
            return [];
        }

        return [
            $this->name => implode(',', $this->values),
        ];
    }

    public function multiple(bool $multiple = true): self
    {
        $this->supportsMultipleValues = $multiple;

        return $this;
    }

    public function supportsMultipleValues(): bool
    {
        return $this->supportsMultipleValues;
    }

    public function input($input): RequestFilterInterface
    {
        if ($this->supportsMultipleValues) {
            $this->values = $this->processMultipleValues($input);
        } else {
            $this->values = $this->processSingleValue($input);
        }

        return $this;
    }

    /**
     * @param $input
     * @return array
     * @throws InvalidFilterInputException
     */
    protected function processMultipleValues($input): array
    {
        if (! is_array($input)) {
            return $this->processSingleValue($input);
        }

        foreach ($input as $value) {
            if (! is_int($value)) {
                throw new InvalidFilterInputException('The ResourceFilter input array must only contain int values');
            }
        }

        return $input;
    }

    protected function processSingleValue($input): array
    {
        if (! is_int($input)) {
            $error = 'The ResourceFilter input must be an int';

            if ($this->supportsMultipleValues) {
                $error .= ' when not given an array';
            }

            throw new InvalidFilterInputException($error);
        }

        return [$input];
    }
}
