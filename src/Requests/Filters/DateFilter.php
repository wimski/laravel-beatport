<?php

namespace Wimski\Beatport\Requests\Filters;

use Carbon\Carbon;
use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Enums\DateFilterPresetEnum;
use Wimski\Beatport\Exceptions\InvalidFilterInputException;

class DateFilter extends AbstractAttributeFilter
{
    /**
     * @var array|string
     */
    protected $value;

    public function name(): string
    {
        return 'date';
    }

    public function queryParams(): array
    {
        if (! $this->value) {
            return [];
        }

        if (is_array($this->value)) {
            return $this->value;
        }

        return [
            'last' => $this->value,
        ];
    }

    public function input($input): RequestFilterInterface
    {
        if (is_array($input)) {
            $this->value = $this->processArray($input);
        } elseif (is_string($input)) {
            $this->value = $this->processString($input);
        } else {
            throw new InvalidFilterInputException('The DateFilter input must be an array or string');
        }

        return $this;
    }

    /**
     * @param array $input
     * @return array
     * @throws InvalidFilterInputException
     */
    protected function processArray(array $input): array
    {
        if (! array_key_exists('start', $input) || ! array_key_exists('end', $input)) {
            throw new InvalidFilterInputException("The DateFilter input array must have the keys 'start' and 'end'");
        }

        $start = Carbon::parse($input['start']);
        $end   = Carbon::parse($input['end']);

        if ($start->greaterThanOrEqualTo($end)) {
            throw new InvalidFilterInputException('The DateFilter input start value must be less than the input end value');
        }

        return [
            'start-date' => $start->format('Y-m-d'),
            'end-date'   => $end->format('Y-m-d'),
        ];
    }

    /**
     * @param string $input
     * @return string
     * @throws InvalidFilterInputException
     */
    protected function processString(string $input): string
    {
        if (! DateFilterPresetEnum::isValid($input)) {
            throw new InvalidFilterInputException('The DateFilter input string must be a valid DateFilterPresetEnum value');
        }

        return $input;
    }
}
