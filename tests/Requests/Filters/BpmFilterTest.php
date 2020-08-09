<?php

namespace Wimski\Beatport\Tests\Requests\Filters;

use Wimski\Beatport\Exceptions\InvalidFilterInputException;
use Wimski\Beatport\Requests\Filters\BpmFilter;
use Wimski\Beatport\Tests\TestCase;

class BpmFilterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_make_an_instance(): void
    {
        static::assertInstanceOf(BpmFilter::class, BpmFilter::make());
    }

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $filter = new BpmFilter();

        static::assertEquals('bpm', $filter->name());
    }

    /**
     * @test
     */
    public function it_returns_query_params(): void
    {
        $filter = new BpmFilter();

        static::assertSame([], $filter->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_accepts_input(): void
    {
        $filter = new BpmFilter();
        $filter->input([
            'low'  => 100,
            'high' => 150,
        ]);

        static::assertSame([
            'bpm-low'  => 100,
            'bpm-high' => 150,
        ], $filter->queryParams());
    }

    /**
     * @test
     * @dataProvider notArrayValues
     */
    public function the_input_must_be_an_array($input): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage('The BpmFilter input must be an array');

        $filter = new BpmFilter();
        $filter->input($input);
    }

    public function notArrayValues(): array
    {
        return [
            ['string'],
            [5],
            [1.23],
            [true],
            [new \stdClass()],
            [null],
        ];
    }

    /**
     * @test
     */
    public function the_input_must_have_low_and_high_keys(): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage("The BpmFilter input array must have the keys 'low' and 'high'");

        $filter = new BpmFilter();
        $filter->input(['key' => 'value']);
    }

    /**
     * @test
     */
    public function the_input_must_have_integer_values(): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage('The BpmFilter input array must have integer values');

        $filter = new BpmFilter();
        $filter->input([
            'low'  => 'abc',
            'high' => 'def',
        ]);
    }

    /**
     * @test
     */
    public function the_input_low_must_be_lower_or_equal_to_the_input_high(): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage('The BpmFilter input low value must be less than the input high value');

        $filter = new BpmFilter();
        $filter->input([
            'low'  => 150,
            'high' => 100,
        ]);
    }
}
