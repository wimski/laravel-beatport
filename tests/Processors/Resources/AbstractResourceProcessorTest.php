<?php

namespace Wimski\Beatport\Tests\Processors\Resources;

use Mockery;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Processors\ResourceUrlProcessor;
use Wimski\Beatport\Tests\Stubs\Classes\ProcessorWithoutMethods;
use Wimski\Beatport\Tests\TestCase;

class AbstractResourceProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_null_for_unsupported_request_types(): void
    {
        $processor = new ProcessorWithoutMethods($this->app->make(ResourceUrlProcessor::class));

        static::assertNull($processor->process(RequestTypeEnum::INDEX(), ''));
        static::assertNull($processor->process(RequestTypeEnum::RELATIONSHIP(), ''));
        static::assertNull($processor->process(RequestTypeEnum::QUERY(), ''));
        static::assertNull($processor->process(RequestTypeEnum::VIEW(), ''));

        /** @var RequestTypeEnum $requestType */
        $requestType = Mockery::mock(RequestTypeEnum::class)
            ->shouldReceive('getValue')
            ->once()
            ->andReturn('foo')
            ->getMock();

        static::assertNull($processor->process($requestType, ''));
    }
}
