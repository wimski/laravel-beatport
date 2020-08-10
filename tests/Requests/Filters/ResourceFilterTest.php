<?php

namespace Wimski\Beatport\Tests\Requests\Filters;

use Wimski\Beatport\Exceptions\InvalidFilterInputException;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Tests\TestCase;

class ResourceFilterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_make_an_instance(): void
    {
        static::assertInstanceOf(ResourceFilter::class, ResourceFilter::make('name'));
    }

    /**
     * @test
     */
    public function it_has_a_name(): void
    {
        $filter = new ResourceFilter('name');

        static::assertSame('name', $filter->name());
    }

    /**
     * @test
     */
    public function it_has_a_key(): void
    {
        $filter = new ResourceFilter('key');

        static::assertSame('key', $filter->key());
    }

    /**
     * @test
     */
    public function it_has_supports_multiple_values(): void
    {
        $filter = new ResourceFilter('');

        static::assertFalse($filter->supportsMultipleValues());

        $filter->multiple();

        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_returns_query_params(): void
    {
        $filter = new ResourceFilter('');

        static::assertSame([], $filter->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_accepts_integer_input_for_single_support(): void
    {
        $filter = new ResourceFilter('name');
        $filter->multiple(false)->input(1);

        static::assertSame([
            'name' => '1',
        ], $filter->queryParams());
    }

    /**
     * @test
     * @dataProvider unsupportedSingleInputValues
     */
    public function it_does_not_accept_other_input_for_single_support($input): void
    {
        static::expectException(InvalidFilterInputException::class);
        static::expectExceptionMessage('The ResourceFilter input must be an int');

        $filter = new ResourceFilter('name');
        $filter->multiple(false)->input($input);
    }

    public function unsupportedSingleInputValues(): array
    {
        return [
            ['string'],
            [1.23],
            [true],
            [[]],
            [new \stdClass()],
            [null],
        ];
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_accepts_integer_input_for_multiple_support(): void
    {
        $filter = new ResourceFilter('name');
        $filter->multiple(true)->input(1);

        static::assertSame([
            'name' => '1',
        ], $filter->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_accepts_array_of_integers_input_for_multiple_support(): void
    {
        $filter = new ResourceFilter('name');
        $filter->multiple(true)->input([1, 2]);

        static::assertSame([
            'name' => '1,2',
        ], $filter->queryParams());
    }

    /**
     * @test
     * @dataProvider unsupportedMultipleInputValues
     */
    public function it_does_not_accept_other_input_for_mutiple_support($input): void
    {
        static::expectException(InvalidFilterInputException::class);

        if (is_array($input)) {
            static::expectExceptionMessage('The ResourceFilter input array must only contain int values');
        } else {
            static::expectExceptionMessage('The ResourceFilter input must be an int when not given an array');
        }

        $filter = new ResourceFilter('name');
        $filter->multiple(true)->input($input);
    }

    public function unsupportedMultipleInputValues(): array
    {
        return [
            ['string'],
            [1.23],
            [true],
            [new \stdClass()],
            [null],
            [['string']],
            [[1.23]],
            [[true]],
            [[[]]],
            [[new \stdClass()]],
            [[null]],
        ];
    }
}
