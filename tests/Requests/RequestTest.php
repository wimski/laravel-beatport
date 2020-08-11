<?php

namespace Wimski\Beatport\Tests\Requests;

use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Mockery;
use Psr\Http\Message\ResponseInterface;
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

        $request = new Request($guzzle, $factory, $paginationProcessor, $this->app->make(Repository::class), $config);

        static::assertInstanceOf(Collection::class, $request->data());

        $request->paginate(PaginationActionEnum::NEXT());
    }
}
