<?php

namespace Wimski\Beatport\Tests\Resources;

use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Resources\SubGenreResource;

class SubGenreResourceTest extends AbstractResourceTest
{
    protected function resourceClass(): string
    {
        return SubGenreResource::class;
    }

    /**
     * @test
     */
    public function it_has_a_type(): void
    {
        static::assertTrue($this->resource->type()->equals(ResourceTypeEnum::SUB_GENRE()));
    }

    /**
     * @test
     */
    public function it_can_index(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            SubGenreResource::all(),
        );
    }

    /**
     * @test
     */
    public function it_has_a_custom_index_path(): void
    {
        $builder = SubGenreResource::all();

        static::assertSame('/tracks/all', $builder->path());
    }

    /**
     * @test
     */
    public function it_can_view(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            SubGenreResource::find('', 1),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            SubGenreResource::findByData($this->getDataMock()),
        );
    }
}
