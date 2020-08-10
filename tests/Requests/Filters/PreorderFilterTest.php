<?php

namespace Wimski\Beatport\Tests\Requests\Filters;

use Wimski\Beatport\Requests\Filters\PreorderFilter;
use Wimski\Beatport\Tests\TestCase;

class PreorderFilterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_make_an_instance(): void
    {
        static::assertInstanceOf(PreorderFilter::class, PreorderFilter::make());
    }

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $filter = new PreorderFilter();

        static::assertSame('preorder', $filter->name());
    }

    /**
     * @test
     */
    public function it_returns_query_params(): void
    {
        $filter = new PreorderFilter();

        static::assertSame([], $filter->queryParams());

        $filter->input(true);

        static::assertSame([
            'preorders' => 'mixed',
        ], $filter->queryParams());
    }
}
