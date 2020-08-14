<?php

namespace Wimski\Beatport\Tests\Resources;

use Mockery;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Tests\TestCase;

abstract class AbstractSharedResourceTest extends TestCase
{
    /**
     * @var ResourceInterface
     */
    protected $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $resourceClass  = $this->resourceClass();
        $this->resource = new $resourceClass();
    }

    abstract protected function resourceClass(): string;

    protected function getDataMock(): DataInterface
    {
        return Mockery::mock(DataInterface::class)
            ->shouldReceive('getSlug')->once()->andReturn('')
            ->shouldReceive('getId')->once()->andReturn(1)
            ->getMock();
    }
}
