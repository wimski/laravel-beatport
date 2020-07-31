<?php

namespace Wimski\Beatport\Resources;

use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Resources\Traits\CanView;

class GenreResource extends AbstractResource
{
    use CanView;

    public function type(): string
    {
        return ResourceTypeEnum::GENRE;
    }
}
