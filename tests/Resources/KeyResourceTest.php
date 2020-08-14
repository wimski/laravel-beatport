<?php

namespace Wimski\Beatport\Tests\Resources;

use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Resources\KeyResource;

class KeyResourceTest extends AbstractResourceTest
{
    protected function resourceClass(): string
    {
        return KeyResource::class;
    }

    /**
     * @test
     */
    public function it_has_a_type(): void
    {
        static::assertTrue($this->resource->type()->equals(ResourceTypeEnum::KEY()));
    }

    /**
     * @test
     */
    public function it_can_index(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            KeyResource::all(),
        );
    }

    /**
     * @test
     */
    public function it_has_a_custom_index_path(): void
    {
        $builder = KeyResource::all();

        static::assertSame('/tracks/all', $builder->path());
    }
}
