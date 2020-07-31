<?php

namespace Wimski\Beatport\Requests\Filters;

use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Exceptions\InvalidFilterInputException;

class BpmFilter extends AbstractAttributeFilter
{
    /**
     * @var array
     */
    protected $values;

    public function name(): string
    {
        return 'bpm';
    }

    public function queryParams(): array
    {
        return $this->values ?: [];
    }

    public function input($input): RequestFilterInterface
    {
        if (! is_array($input)) {
            throw new InvalidFilterInputException('The BpmFilter input must be an array');
        }

        if (! array_key_exists('low', $input) || ! array_key_exists('high', $input)) {
            throw new InvalidFilterInputException("The BpmFilter input array must have the keys 'low' and 'high'");
        }

        $low  = $input['low'];
        $high = $input['high'];

        if (! is_int($low) || ! is_int($high)) {
            throw new InvalidFilterInputException('The BpmFilter input array must have integer values');
        }

        if ($low >= $high) {
            throw new InvalidFilterInputException('The BpmFilter input low value must be less than the input high value');
        }

        $this->values = [
            'bpm-low'  => $low,
            'bpm-high' => $high,
        ];

        return $this;
    }
}
