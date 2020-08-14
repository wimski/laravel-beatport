<?php

namespace Wimski\Beatport\Tests\Stubs\Classes;

use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Resources\AbstractResource;
use Wimski\Beatport\Resources\Traits\CanRelate;

class UseCanRelateTrait extends AbstractResource
{
    use CanRelate;

    public function type(): ResourceTypeEnum
    {
        return ResourceTypeEnum::TRACK();
    }
}
