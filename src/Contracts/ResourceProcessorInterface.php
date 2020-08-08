<?php

namespace Wimski\Beatport\Contracts;

use Illuminate\Support\Collection;
use Wimski\Beatport\Enums\RequestTypeEnum;

interface ResourceProcessorInterface
{
    /**
     * @param RequestTypeEnum $requestType
     * @param string $html
     * @return Collection<DataInterface>|DataInterface|null
     */
    public function process(RequestTypeEnum $requestType, string $html);
}
