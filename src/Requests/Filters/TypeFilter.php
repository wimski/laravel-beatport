<?php

namespace Wimski\Beatport\Requests\Filters;

use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Enums\TypeFilterPresetEnum;
use Wimski\Beatport\Exceptions\InvalidFilterInputException;

class TypeFilter extends AbstractAttributeFilter
{
    /**
     * @var string
     */
    protected $value;

    public function name(): string
    {
        return 'type';
    }

    public function queryParams(): array
    {
        if (! $this->value) {
            return [];
        }

        return [
            'type' => $this->value,
        ];
    }

    public function input($input): RequestFilterInterface
    {
        if (! TypeFilterPresetEnum::isValid($input)) {
            throw new InvalidFilterInputException('The TypeFilter input must be a valid TypeFilterPresetEnum value');
        }

        $this->value = (new TypeFilterPresetEnum($input))->getValue();

        return $this;
    }
}
