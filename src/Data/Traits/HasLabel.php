<?php

namespace Wimski\Beatport\Data\Traits;

use Wimski\Beatport\Data\Label;

trait HasLabel
{
    /**
     * @var Label
     */
    protected $label;

    public function getLabel(): ?Label
    {
        return $this->label;
    }

    public function setLabel(Label $label = null): self
    {
        $this->label = $label;

        return $this;
    }
}
