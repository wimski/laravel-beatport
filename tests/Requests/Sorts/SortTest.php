<?php

namespace Wimski\Beatport\Tests\Requests\Sorts;

use Wimski\Beatport\Exceptions\InvalidSortDirectionException;
use Wimski\Beatport\Requests\Sorts\Sort;
use Wimski\Beatport\Tests\TestCase;

class SortTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_make_an_instance(): void
    {
        static::assertInstanceOf(Sort::class, Sort::make('name'));
    }

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $sort = new Sort('name');

        static::assertSame('name', $sort->name());
    }

    /**
     * @test
     */
    public function it_returns_query_params_direction(): void
    {
        $sort = new Sort('name');

        static::assertSame([
            'sort' => 'name-asc',
        ], $sort->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params_direction
     */
    public function it_can_set_a_direction(): void
    {
        $sort = new Sort('name');

        $sort->direction('asc');
        static::assertSame([
            'sort' => 'name-asc',
        ], $sort->queryParams());

        $sort->direction('desc');
        static::assertSame([
            'sort' => 'name-desc',
        ], $sort->queryParams());

        static::expectException(InvalidSortDirectionException::class);
        static::expectExceptionMessage("The sort direction must be 'asc' or 'desc'");

        $sort->direction('foo');
    }
}
