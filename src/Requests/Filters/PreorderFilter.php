<?php

namespace Wimski\Beatport\Requests\Filters;

use Wimski\Beatport\Contracts\RequestFilterInterface;

class PreorderFilter extends AbstractAttributeFilter
{
    /**
     * @var bool
     */
    protected $preorder;

    public function name(): string
    {
        return 'preorder';
    }

    public function queryParams(): array
    {
        if ($this->preorder !== true) {
            return [];
        }

        return [
            'preorders' => 'mixed',
        ];
    }

    public function input($input): RequestFilterInterface
    {
        $this->preorder = $input === true;

        return $this;
    }
}
