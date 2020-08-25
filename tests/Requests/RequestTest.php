<?php

namespace Wimski\Beatport\Tests\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Mockery;
use Psr\Http\Message\ResponseInterface;
use Swis\Guzzle\Fixture\Handler;
use Swis\Guzzle\Fixture\ResponseBuilder;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Contracts\ResourceProcessorFactoryInterface;
use Wimski\Beatport\Contracts\ResourceProcessorInterface;
use Wimski\Beatport\Enums\PaginationActionEnum;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Processors\PaginationProcessor;
use Wimski\Beatport\Requests\Pagination;
use Wimski\Beatport\Requests\Request;
use Wimski\Beatport\Requests\RequestConfig;
use Wimski\Beatport\Tests\TestCase;

class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_works(): void
    {
        $resourceType = Mockery::mock(ResourceTypeEnum::class);
        $requestType  = Mockery::mock(RequestTypeEnum::class);

        $config = new RequestConfig($resourceType, $requestType, true, '/foo', [
            'key' => 'value',
        ]);

        $response = Mockery::mock(ResponseInterface::class)
            ->shouldReceive('getBody')
            ->twice()
            ->andReturn('lorem ipsum dolor sit amet')
            ->getMock();

        /** @var ClientInterface $guzzle */
        $guzzle = Mockery::mock(ClientInterface::class)
            ->shouldReceive('request')
            ->once()
            ->with('GET', 'https://www.beatport.com/foo', [
                'headers' => ['Accept-Language' => 'en-US,en'],
                'query'   => ['key' => 'value'],
            ])
            ->andReturn($response)
            ->shouldReceive('request')
            ->once()
            ->with('GET', 'https://www.beatport.com/foo', [
                'headers' => ['Accept-Language' => 'en-US,en'],
                'query'   => ['key' => 'value', 'page' => 2],
            ])
            ->andReturn($response)
            ->getMock();

        $resourceProcessor = Mockery::mock(ResourceProcessorInterface::class)
            ->shouldReceive('process')
            ->twice()
            ->with($requestType, 'lorem ipsum dolor sit amet')
            ->andReturn(new Collection())
            ->getMock();

        /** @var ResourceProcessorFactoryInterface $factory */
        $factory = Mockery::mock(ResourceProcessorFactoryInterface::class)
            ->shouldReceive('make')
            ->once()
            ->with($resourceType)
            ->andReturn($resourceProcessor)
            ->getMock();

        $pagination = Mockery::mock(Pagination::class)
            ->shouldReceive('next')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('current')
            ->twice()
            ->andReturn(2)
            ->getMock();

        /** @var PaginationProcessor $paginationProcessor */
        $paginationProcessor = Mockery::mock(PaginationProcessor::class)
            ->shouldReceive('process')
            ->once()
            ->with('lorem ipsum dolor sit amet')
            ->andReturn($pagination)
            ->getMock();

        $request = new Request($guzzle, $factory, $paginationProcessor, $this->app->make(Repository::class));
        $request->startWithConfig($config);

        static::assertInstanceOf(Collection::class, $request->data());
        static::assertSame('lorem ipsum dolor sit amet', $request->response());

        $request->paginate(PaginationActionEnum::NEXT());
    }

    /**
     * @test
     */
    public function it_returns_a_response(): void
    {
        $config = new RequestConfig(
            ResourceTypeEnum::TRACK(),
            RequestTypeEnum::INDEX(),
            true,
            '/tracks/all',
            [],
        );

        $request = $this->getRequest($config);

        static::assertSame(
            file_get_contents($this->getResponsesPath() . '/www.beatport.com/tracks/all.get.mock'),
            $request->response(),
        );
    }

    /**
     * @test
     */
    public function it_returns_data(): void
    {
        $config = new RequestConfig(
            ResourceTypeEnum::TRACK(),
            RequestTypeEnum::INDEX(),
            true,
            '/tracks/all',
            [],
        );

        $request = $this->getRequest($config);

        static::assertCount(2, $request->data());
    }

    /**
     * @test
     */
    public function it_does_not_paginate_when_it_has_no_pagination(): void
    {
        $config = new RequestConfig(
            ResourceTypeEnum::TRACK(),
            RequestTypeEnum::INDEX(),
            true,
            '/track/slug/123',
            [],
        );

        $request = $this->getRequest($config);

        $response = $request->response();

        $request->paginate('next');

        static::assertSame($response, $request->response());
    }

    /**
     * @test
     */
    public function it_has_pagination_info(): void
    {
        $config = new RequestConfig(
            ResourceTypeEnum::TRACK(),
            RequestTypeEnum::INDEX(),
            true,
            '/tracks/all',
            [],
        );

        $request = $this->getRequest($config);

        static::assertTrue($request->hasPagination());
        static::assertSame(1, $request->currentPage());
        static::assertSame(3, $request->totalPages());
    }

    /**
     * @test
     */
    public function it_transforms_to_array(): void
    {
        $config = new RequestConfig(
            ResourceTypeEnum::TRACK(),
            RequestTypeEnum::INDEX(),
            true,
            '/tracks/all',
            ['per-page' => 25],
        );

        $request = $this->getRequest($config);

        static::assertSame([
            'resourceType' => 'track',
            'requestType'   => 'index',
            'path'          => '/tracks/all',
            'queryParams'   => ['per-page' => 25],
            'hasPagination' => true,
            'currentPage'   => 1,
            'totalPages'    => 3,
        ], $request->toArray());
    }

    /**
     * @test
     */
    public function it_transform_from_array(): void
    {
        /** @var ResourceProcessorFactoryInterface $factory */
        $factory = Mockery::mock(ResourceProcessorFactoryInterface::class)
            ->shouldReceive('make')
            ->once()
            ->withArgs(function ($type) {
                return $type instanceof ResourceTypeEnum
                    && $type->getValue() === 'track';
            })
            ->getMock();

        $request = new Request(
            Mockery::mock(ClientInterface::class),
            $factory,
            Mockery::mock(PaginationProcessor::class),
            Mockery::mock(Repository::class),
        );

        $request->fromArray([
            'resourceType' => 'track',
            'requestType'   => 'index',
            'path'          => '/tracks/all',
            'queryParams'   => ['per-page' => 25],
            'hasPagination' => true,
            'currentPage'   => 1,
            'totalPages'    => 3,
        ]);

        static::assertTrue($request->hasPagination());
        static::assertSame(1, $request->currentPage());
        static::assertSame(3, $request->totalPages());
    }

    protected function getRequest(RequestConfig $config): RequestInterface
    {
        $responseBuilder = new ResponseBuilder($this->getResponsesPath());
        $handler         = new Handler($responseBuilder);
        $handlerStack    = HandlerStack::create($handler);
        $guzzleClient    = new Client(['handler' => $handlerStack]);

        /** @var Repository $appConfig */
        $appConfig = Mockery::mock(Repository::class)
            ->shouldReceive('get')
            ->with('beatport.url')
            ->andReturn('https://www.beatport.com')
            ->getMock();

        $request = new Request(
            $guzzleClient,
            $this->app->make(ResourceProcessorFactoryInterface::class),
            $this->app->make(PaginationProcessor::class),
            $appConfig,
        );

        return $request->startWithConfig($config);
    }
}
