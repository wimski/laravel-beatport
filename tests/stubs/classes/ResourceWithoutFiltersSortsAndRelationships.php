<?php

namespace Wimski\Beatport\Tests\Stubs\Classes;

use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Resources\AbstractResource;

class ResourceWithoutFiltersSortsAndRelationships extends AbstractResource
{
    public function type(): ResourceTypeEnum
    {
        return ResourceTypeEnum::TRACK();
    }
}
