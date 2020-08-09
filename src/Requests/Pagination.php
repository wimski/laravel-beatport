<?php

namespace Wimski\Beatport\Requests;

class Pagination
{
    /**
     * @var int
     */
    protected $current;

    /**
     * @var int
     */
    protected $total;

    public function __construct(int $current, int $total)
    {
        $this->current = $current;
        $this->total   = $total;
    }

    public function current(): int
    {
        return $this->current;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function page(int $page): self
    {
        $this->current = max(1, min($this->total, $page));

        return $this;
    }

    public function first(): self
    {
        $this->current = 1;

        return $this;
    }

    public function last(): self
    {
        $this->current = $this->total;

        return $this;
    }

    public function next(): self
    {
        return $this->add(1);
    }

    public function prev(): self
    {
        return $this->sub(1);
    }

    public function add(int $count): self
    {
        return $this->page($this->current + $count);
    }

    public function sub(int $count): self
    {
        return $this->page($this->current - $count);
    }
}
