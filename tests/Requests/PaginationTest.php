<?php

namespace Wimski\Beatport\Tests\Requests;

use Wimski\Beatport\Requests\Pagination;
use Wimski\Beatport\Tests\TestCase;

class PaginationTest extends TestCase
{
    /**
     * @var Pagination
     */
    protected $pagination;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pagination = new Pagination(31, 96);
    }

    /**
     * @test
     */
    public function it_has_a_current_page(): void
    {
        static::assertEquals(31, $this->pagination->current());
    }

    /**
     * @test
     */
    public function it_has_a_total_page_count(): void
    {
        static::assertEquals(96, $this->pagination->total());
    }

    /**
     * @test
     */
    public function it_updates_the_current_page(): void
    {
        static::assertEquals(47, $this->pagination->page(47)->current());
    }

    /**
     * @test
     */
    public function it_does_not_update_the_current_page_to_lower_than_one(): void
    {
        static::assertEquals(1, $this->pagination->page(-1)->current());
    }

    /**
     * @test
     */
    public function it_does_not_update_the_current_page_to_higher_than_total(): void
    {
        static::assertEquals(96, $this->pagination->page(100)->current());
    }

    /**
     * @test
     */
    public function it_updates_the_current_page_to_first(): void
    {
        static::assertEquals(1, $this->pagination->first()->current());
    }

    /**
     * @test
     */
    public function it_updates_the_current_page_to_last(): void
    {
        static::assertEquals(96, $this->pagination->last()->current());
    }

    /**
     * @test
     */
    public function it_updates_the_current_page_to_next(): void
    {
        static::assertEquals(32, $this->pagination->next()->current());
    }

    /**
     * @test
     */
    public function it_updates_the_current_page_to_prev(): void
    {
        static::assertEquals(30, $this->pagination->prev()->current());
    }

    /**
     * @test
     */
    public function it_adds_pages(): void
    {
        static::assertEquals(34, $this->pagination->add(3)->current());
    }

    /**
     * @test
     */
    public function it_subtracts_pages(): void
    {
        static::assertEquals(18, $this->pagination->sub(13)->current());
    }
}
