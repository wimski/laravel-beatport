<?php

namespace Wimski\Beatport\Tests\Requests;

use Mockery;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\RequestConfig;
use Wimski\Beatport\Tests\TestCase;

class RequestConfigTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_properties(): void
    {
        $resourceType = Mockery::mock(ResourceTypeEnum::class);
        $requestType  = Mockery::mock(RequestTypeEnum::class);

        $config = new RequestConfig(
            $resourceType,
            $requestType,
            true,
            'path',
            ['key' => 'value'],
        );

        static::assertSame($resourceType, $config->resourceType());
        static::assertSame($requestType, $config->requestType());
        static::assertTrue($config->canHavePagination());
        static::assertSame('path', $config->path());
        static::assertSame(['key' => 'value'], $config->queryParams());
    }
}
