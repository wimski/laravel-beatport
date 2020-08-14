<?php

namespace Wimski\Beatport\Data;

use Illuminate\Support\Str;
use Wimski\Beatport\Contracts\DataInterface;

abstract class AbstractData implements DataInterface
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var int
     */
    protected $id;

    public function __construct(array $properties = null)
    {
        $this->setProperties($properties);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title = null): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug = null): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id = null): self
    {
        $this->id = $id;

        return $this;
    }

    protected function setProperties(array $properties = null): self
    {
        if (empty($properties)) {
            return $this;
        }

        foreach ($properties as $key => $value) {
            $setter = 'set' . Str::studly($key);

            if (! method_exists($this, $setter)) {
                continue;
            }

            call_user_func([$this, $setter], $value);
        }

        return $this;
    }
}
