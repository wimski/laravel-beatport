<?php

namespace Wimski\Beatport\Requests\Builders;

use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;

class RelationshipRequestBuilder extends AbstractRequestBuilder
{
    /**
     * @var bool
     */
    protected $multipleResults = true;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var ResourceInterface
     */
    protected $parent;

    public function type(): string
    {
        return RequestTypeEnum::INDEX;
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

    public function parent(ResourceInterface $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function path(): string
    {
        return implode('/', [
            '',
            $this->parent->type(),
            $this->slug,
            $this->id,
            (new ResourceTypeEnum($this->resource->type()))->getValuePlural(),
        ]);
    }
}
