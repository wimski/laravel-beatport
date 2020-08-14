<?php

namespace Wimski\Beatport\Requests\Builders;

use Wimski\Beatport\Enums\RequestTypeEnum;

class ViewRequestBuilder extends AbstractRequestBuilder
{
    /**
     * @var string
     */
    protected $slug;

    /**
     * @var int
     */
    protected $id;

    public function type(): RequestTypeEnum
    {
        return RequestTypeEnum::VIEW();
    }

    public function slug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function id(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function path(): string
    {
        return parent::path() ?? implode('/', [
            '',
            $this->resource->type()->getValue(),
            $this->slug,
            $this->id,
        ]);
    }
}
