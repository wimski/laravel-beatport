<?php

namespace Wimski\Beatport\Tests\Resources;

use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Tests\Stubs\Classes\ResourceWithoutFiltersSortsAndRelationships;
use Wimski\Beatport\Tests\TestCase;

class AbstractResourceTest extends TestCase
{
    /**
     * @test
     */
    public function it_does_not_find_non_existing_filter(): void
    {
        $resource = new ResourceWithoutFiltersSortsAndRelationships();

        static::assertNull($resource->getFilter(RequestTypeEnum::INDEX(), ''));
        static::assertNull($resource->getFilter(RequestTypeEnum::RELATIONSHIP(), ''));
        static::assertNull($resource->getFilter(RequestTypeEnum::QUERY(), ''));
        static::assertNull($resource->getFilter(RequestTypeEnum::VIEW(), ''));
    }

    /**
     * @test
     */
    public function it_does_not_find_non_existing_sort(): void
    {
        $resource = new ResourceWithoutFiltersSortsAndRelationships();

        static::assertNull($resource->getSort(RequestTypeEnum::INDEX(), ''));
        static::assertNull($resource->getSort(RequestTypeEnum::RELATIONSHIP(), ''));
        static::assertNull($resource->getSort(RequestTypeEnum::QUERY(), ''));
        static::assertNull($resource->getSort(RequestTypeEnum::VIEW(), ''));
    }

    /**
     * @test
     */
    public function it_does_not_find_non_existing_relationship(): void
    {
        $resource = new ResourceWithoutFiltersSortsAndRelationships();

        static::assertFalse($resource->hasRelationship(''));
    }
}
