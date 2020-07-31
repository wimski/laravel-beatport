<?php

namespace Wimski\Beatport\Contracts;

use Illuminate\Support\Collection;

interface ProcessorInterface
{
    /**
     * @param string $html
     * @param bool $multipleResources
     * @return Collection<DataInterface>|DataInterface|null
     */
    public function process(string $html, bool $multipleResources);
}
