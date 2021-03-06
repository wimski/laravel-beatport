<?php

namespace Wimski\Beatport\Tests\Requests\Filters;

use Wimski\Beatport\Enums\TypeFilterPresetEnum;
use Wimski\Beatport\Exceptions\InvalidFilterInputException;
use Wimski\Beatport\Requests\Filters\TypeFilter;
use Wimski\Beatport\Tests\TestCase;

class TypeFilterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_make_an_instance(): void
    {
        static::assertInstanceOf(TypeFilter::class, TypeFilter::make());
    }

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $filter = new TypeFilter();

        static::assertSame('type', $filter->name());
    }

    /**
     * @test
     */
    public function it_returns_query_params(): void
    {
        $filter = new TypeFilter();

        static::assertSame([], $filter->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_accepts_input_as_int(): void
    {
        $filter = new TypeFilter();
        $filter->input(2);

        static::assertSame([
            'type' => 2,
        ], $filter->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_accepts_input_as_enum(): void
    {
        $filter = new TypeFilter();
        $filter->input(TypeFilterPresetEnum::ALBUM());

        static::assertSame([
            'type' => 2,
        ], $filter->queryParams());
    }

    /**
     * @test
     */
    public function the_input_must_be_a_type_filter_preset(): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage('The TypeFilter input must be a valid TypeFilterPresetEnum value');

        $filter = new TypeFilter();
        $filter->input(99999);
    }
}
