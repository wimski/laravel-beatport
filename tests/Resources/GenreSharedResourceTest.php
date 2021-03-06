<?php

namespace Wimski\Beatport\Tests\Resources;

use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Resources\GenreResource;
use Wimski\Beatport\Resources\ReleaseResource;
use Wimski\Beatport\Resources\TrackResource;

class GenreSharedResourceTest extends AbstractSharedResourceTest
{
    protected function resourceClass(): string
    {
        return GenreResource::class;
    }

    /**
     * @test
     */
    public function it_has_a_type(): void
    {
        static::assertTrue($this->resource->type()->equals(ResourceTypeEnum::GENRE()));
    }

    /**
     * @test
     */
    public function it_has_relationship_release(): void
    {
        static::assertTrue($this->resource->hasRelationship(ReleaseResource::class));
    }

    /**
     * @test
     */
    public function it_has_relationship_track(): void
    {
        static::assertTrue($this->resource->hasRelationship(TrackResource::class));
    }

    /**
     * @test
     * @depends it_has_relationship_release
     */
    public function it_can_relate_releases(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            GenreResource::relationship('', 1, ReleaseResource::class),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            GenreResource::relationshipByData($this->getDataMock(), ReleaseResource::class),
        );
    }

    /**
     * @test
     * @depends it_has_relationship_track
     */
    public function it_can_relate_traks(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            GenreResource::relationship('', 1, TrackResource::class),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            GenreResource::relationshipByData($this->getDataMock(), TrackResource::class),
        );
    }

    /**
     * @test
     */
    public function it_can_index(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            GenreResource::all(),
        );
    }

    /**
     * @test
     */
    public function it_can_view(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            GenreResource::find('', 1),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            GenreResource::findByData($this->getDataMock()),
        );
    }
}
