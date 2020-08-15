<?php

namespace Wimski\Beatport\Tests\Requests\Builders;

use Illuminate\Contracts\Container\Container;
use Mockery;
use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\RequestPageSizeEnum;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Exceptions\InvalidFilterException;
use Wimski\Beatport\Exceptions\InvalidSortException;
use Wimski\Beatport\Requests\Builders\SearchRequestBuilder;
use Wimski\Beatport\Requests\RequestConfig;
use Wimski\Beatport\Tests\TestCase;

class SearchRequestBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_type(): void
    {
        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), Mockery::mock(ResourceInterface::class));

        static::assertTrue($builder->type()->equals(RequestTypeEnum::QUERY()));
    }

    /**
     * @test
     */
    public function it_returns_a_resource(): void
    {
        $resource = Mockery::mock(ResourceInterface::class);

        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), $resource);

        static::assertInstanceOf(ResourceInterface::class, $builder->resource());
    }

    /**
     * @test
     */
    public function it_can_have_pagination(): void
    {
        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), Mockery::mock(ResourceInterface::class));

        static::assertTrue($builder->canHavePagination());
    }

    /**
     * @test
     */
    public function it_returns_query_params(): void
    {
        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), Mockery::mock(ResourceInterface::class));

        static::assertSame([
            'per-page' => 25,
            'q'        => null,
        ], $builder->queryParams());
    }

    /**
     * @test
     */
    public function it_returns_a_path(): void
    {
        $enum = Mockery::mock(ResourceTypeEnum::class)
            ->shouldReceive('getValuePlural')
            ->once()
            ->andReturn('foobars')
            ->getMock();

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('type')
            ->once()
            ->andReturn($enum)
            ->getMock();

        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), $resource);

        static::assertSame('/search/foobars', $builder->path());
    }

    /**
     * @test
     */
    public function it_returns_a_custom_path(): void
    {
        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), Mockery::mock(ResourceInterface::class));
        $builder->customPath('foobar');

        static::assertSame('foobar', $builder->path());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_has_a_query(): void
    {
        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), Mockery::mock(ResourceInterface::class));
        $builder->query('foo');

        static::assertSame([
            'per-page' => 25,
            'q'        => 'foo',
        ], $builder->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_adds_a_filter(): void
    {
        $filter = Mockery::mock(RequestFilterInterface::class)
            ->shouldReceive('input')
            ->once()
            ->with('bar')
            ->andReturnSelf()
            ->shouldReceive('queryParams')
            ->once()
            ->andReturn(['foo' => 'bar'])
            ->getMock();

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('getFilter')
            ->once()
            ->withArgs(function (RequestTypeEnum $type, string $name) {
                return $type->equals(RequestTypeEnum::QUERY()) && $name === 'foo';
            })
            ->andReturn($filter)
            ->getMock();

        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), $resource);
        $builder->filter('foo', 'bar');

        static::assertSame([
            'per-page' => 25,
            'foo'      => 'bar',
            'q'        => null,
        ], $builder->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_does_not_add_the_same_filter_twice(): void
    {
        $filter1 = Mockery::mock(RequestFilterInterface::class)
            ->shouldReceive('input')
            ->once()
            ->with('bar')
            ->andReturnSelf()
            ->shouldReceive('name')
            ->once()
            ->andReturn('foo')
            ->getMock();

        $filter2 = Mockery::mock(RequestFilterInterface::class)
            ->shouldReceive('input')
            ->once()
            ->with('lipsum')
            ->andReturnSelf()
            ->shouldReceive('queryParams')
            ->once()
            ->andReturn(['foo' => 'lipsum'])
            ->getMock();

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('getFilter')
            ->twice()
            ->withArgs(function (RequestTypeEnum $type, string $name) {
                return $type->equals(RequestTypeEnum::QUERY()) && $name === 'foo';
            })
            ->andReturn($filter1, $filter2)
            ->getMock();

        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), $resource);
        $builder->filter('foo', 'bar');
        $builder->filter('foo', 'lipsum');

        static::assertSame([
            'per-page' => 25,
            'foo'      => 'lipsum',
            'q'        => null,
        ], $builder->queryParams());
    }

    /**
     * @test
     */
    public function it_does_not_add_unsupported_filters(): void
    {
        static::expectException(InvalidFilterException::class);
        static::expectExceptionMessage('Filter foo is not supported for ');

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('getFilter')
            ->once()
            ->withArgs(function (RequestTypeEnum $type, string $name) {
                return $type->equals(RequestTypeEnum::QUERY()) && $name === 'foo';
            })
            ->andReturnNull()
            ->getMock();

        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), $resource);
        $builder->filter('foo', 'bar');
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_sets_a_sort(): void
    {
        $sort = Mockery::mock(RequestSortInterface::class)
            ->shouldReceive('direction')
            ->once()
            ->with('asc')
            ->andReturnSelf()
            ->shouldReceive('queryParams')
            ->once()
            ->andReturn(['foo' => 'asc'])
            ->getMock();

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('getSort')
            ->once()
            ->withArgs(function (RequestTypeEnum $type, string $name) {
                return $type->equals(RequestTypeEnum::QUERY()) && $name === 'foo';
            })
            ->andReturn($sort)
            ->getMock();

        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), $resource);
        $builder->sort('foo');

        static::assertSame([
            'per-page' => 25,
            'foo'      => 'asc',
            'q'        => null,
        ], $builder->queryParams());
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_sets_only_one_sort(): void
    {
        $sort1 = Mockery::mock(RequestSortInterface::class)
            ->shouldReceive('direction')
            ->once()
            ->with('asc')
            ->andReturnSelf()
            ->getMock();

        $sort2 = Mockery::mock(RequestSortInterface::class)
            ->shouldReceive('direction')
            ->once()
            ->with('desc')
            ->andReturnSelf()
            ->shouldReceive('queryParams')
            ->once()
            ->andReturn(['foo' => 'desc'])
            ->getMock();

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('getSort')
            ->twice()
            ->withArgs(function (RequestTypeEnum $type, string $name) {
                return $type->equals(RequestTypeEnum::QUERY()) && $name === 'foo';
            })
            ->andReturn($sort1, $sort2)
            ->getMock();

        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), $resource);
        $builder->sort('foo');
        $builder->sort('foo', 'desc');

        static::assertSame([
            'per-page' => 25,
            'foo'      => 'desc',
            'q'        => null,
        ], $builder->queryParams());
    }

    /**
     * @test
     */
    public function it_does_not_add_unsupported_sorts(): void
    {
        static::expectException(InvalidSortException::class);
        static::expectExceptionMessage('Sort foo is not supported for ');

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('getSort')
            ->once()
            ->withArgs(function (RequestTypeEnum $type, string $name) {
                return $type->equals(RequestTypeEnum::QUERY()) && $name === 'foo';
            })
            ->andReturnNull()
            ->getMock();

        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), $resource);
        $builder->sort('foo');
    }

    /**
     * @test
     * @depends it_returns_query_params
     */
    public function it_sets_a_page_size(): void
    {
        $builder = new SearchRequestBuilder(Mockery::mock(Container::class), Mockery::mock(ResourceInterface::class));

        static::assertSame([
            'per-page' => 25,
            'q'        => null,
        ], $builder->queryParams());

        $builder->pageSize(RequestPageSizeEnum::PAGE_SIZE_100());

        static::assertSame([
            'per-page' => 100,
            'q'        => null,
        ], $builder->queryParams());
    }

    /**
     * @test
     * @depends it_returns_a_type
     * @depends it_can_have_pagination
     * @depends it_returns_query_params
     * @depends it_returns_a_path
     */
    public function it_returns_a_request(): void
    {
        $enum = Mockery::mock(ResourceTypeEnum::class)
            ->shouldReceive('getValue')
            ->once()
            ->andReturn('foobar')
            ->shouldReceive('getValuePlural')
            ->once()
            ->andReturn('foobars')
            ->getMock();

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('type')
            ->twice()
            ->andReturn($enum)
            ->getMock();

        $request = Mockery::mock(RequestInterface::class);

        /** @var Container $app */
        $app = Mockery::mock(Container::class)
            ->shouldReceive('make')
            ->once()
            ->withArgs(function (string $class, array $args) {
                return $class === RequestInterface::class
                    && array_key_exists('requestConfig', $args)
                    && $args['requestConfig'] instanceOf RequestConfig
                    && $args['requestConfig']->resourceType()->getValue() === 'foobar'
                    && $args['requestConfig']->requestType()->equals(RequestTypeEnum::QUERY())
                    && $args['requestConfig']->canHavePagination() === true
                    && $args['requestConfig']->path() === '/search/foobars'
                    && $args['requestConfig']->queryParams() === [
                        'per-page' => 25,
                        'q'        => null,
                    ];
            })
            ->andReturn($request)
            ->getMock();

        $builder = new SearchRequestBuilder($app, $resource);

        static::assertInstanceOf(RequestInterface::class, $builder->get());
    }
}
