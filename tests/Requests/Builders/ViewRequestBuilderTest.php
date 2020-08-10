<?php

namespace Wimski\Beatport\Tests\Requests\Builders;

use Illuminate\Contracts\Container\Container;
use Mockery;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Builders\ViewRequestBuilder;
use Wimski\Beatport\Requests\RequestConfig;
use Wimski\Beatport\Tests\TestCase;

class ViewRequestBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_type(): void
    {
        $builder = new ViewRequestBuilder(Mockery::mock(Container::class), Mockery::mock(ResourceInterface::class));

        static::assertTrue($builder->type()->equals(RequestTypeEnum::VIEW()));
    }

    /**
     * @test
     */
    public function it_returns_a_resource(): void
    {
        $resource = Mockery::mock(ResourceInterface::class);

        $builder = new ViewRequestBuilder(Mockery::mock(Container::class), $resource);

        static::assertInstanceOf(ResourceInterface::class, $builder->resource());
    }

    /**
     * @test
     */
    public function it_can_not_have_pagination(): void
    {
        $builder = new ViewRequestBuilder(Mockery::mock(Container::class), Mockery::mock(ResourceInterface::class));

        static::assertFalse($builder->canHavePagination());
    }

    /**
     * @test
     */
    public function it_returns_query_params(): void
    {
        $builder = new ViewRequestBuilder(Mockery::mock(Container::class), Mockery::mock(ResourceInterface::class));

        static::assertSame([], $builder->queryParams());
    }

    /**
     * @test
     */
    public function it_returns_a_path(): void
    {
        $enum = Mockery::mock(ResourceTypeEnum::class)
            ->shouldReceive('getValue')
            ->once()
            ->andReturn('foobar')
            ->getMock();

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('type')
            ->once()
            ->andReturn($enum)
            ->getMock();

        $builder = new ViewRequestBuilder(Mockery::mock(Container::class), $resource);
        $builder->slug('slug')->id(1);

        static::assertSame('/foobar/slug/1', $builder->path());
    }

    /**
     * @test
     * @depends it_returns_a_type
     * @depends it_can_not_have_pagination
     * @depends it_returns_query_params
     * @depends it_returns_a_path
     */
    public function it_returns_a_request(): void
    {
        $enum = Mockery::mock(ResourceTypeEnum::class)
            ->shouldReceive('getValue')
            ->twice()
            ->andReturn('foobar')
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
                    && array_key_exists('config', $args)
                    && $args['config'] instanceOf RequestConfig
                    && $args['config']->resourceType()->getValue() === 'foobar'
                    && $args['config']->requestType()->equals(RequestTypeEnum::VIEW())
                    && $args['config']->canHavePagination() === false
                    && $args['config']->path() === '/foobar/slug/1'
                    && $args['config']->queryParams() === [];
            })
            ->andReturn($request)
            ->getMock();

        $builder = new ViewRequestBuilder($app, $resource);
        $builder->slug('slug')->id(1);

        static::assertInstanceOf(RequestInterface::class, $builder->get());
    }
}
