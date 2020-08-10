<?php

namespace Wimski\Beatport\Tests\Requests\Filters;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Wimski\Beatport\Exceptions\InvalidFilterInputException;
use Wimski\Beatport\Requests\Filters\DateFilter;
use Wimski\Beatport\Tests\TestCase;

class DateFilterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_make_an_instance(): void
    {
        static::assertInstanceOf(DateFilter::class, DateFilter::make());
    }

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $filter = new DateFilter();

        static::assertSame('date', $filter->name());
    }

    /**
     * @test
     */
    public function it_returns_query_params(): void
    {
        $filter = new DateFilter();

        static::assertSame([], $filter->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_returns_query_params_for_string_input(): void
    {
        $filter = new DateFilter();
        $filter->input('7d');

        static::assertSame([
            'last' => '7d',
        ], $filter->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_returns_query_params_for_array_input(): void
    {
        $filter = new DateFilter();
        $filter->input([
            'start' => Carbon::create(2020, 7, 1),
            'end'   => Carbon::create(2020, 7, 31),
        ]);

        static::assertSame([
            'start-date' => '2020-07-01',
            'end-date'   => '2020-07-31',
        ], $filter->queryParams());
    }

    /**
     * @test
     * @dataProvider unsupportedInput
     */
    public function it_accepts_an_array_or_string_as_input($input): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage('The DateFilter input must be an array or string');

        $filter = new DateFilter();
        $filter->input($input);
    }

    public function unsupportedInput(): array
    {
        return [
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
    public function the_input_string_must_be_a_date_filter_preset(): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage('The DateFilter input string must be a valid DateFilterPresetEnum value');

        $filter = new DateFilter();
        $filter->input('foo');
    }

    /**
     * @test
     */
    public function the_input_array_must_have_start_and_end_keys(): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage("The DateFilter input array must have the keys 'start' and 'end'");

        $filter = new DateFilter();
        $filter->input(['key' => 'value']);
    }

    /**
     * @test
     */
    public function the_input_array_values_must_have_valid_date_format(): void
    {
        static::expectException(InvalidFormatException::class);

        $filter = new DateFilter();
        $filter->input([
            'start' => 'foo',
            'end'   => 'bar',
        ]);
    }

    /**
     * @test
     */
    public function the_input_start_must_be_lower_or_equal_to_the_input_end(): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage('The DateFilter input start value must be less than the input end value');

        $filter = new DateFilter();
        $filter->input([
            'start' => Carbon::create(2020, 7, 31),
            'end'   => Carbon::create(2020, 7, 1),
        ]);
    }
}
