<?php

namespace Wimski\Beatport\Contracts;

interface DataInterface
{
    public function getSlug(): ?string;

    public function getId(): ?int;
}
