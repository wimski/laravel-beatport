<?php

namespace Wimski\Beatport\Tests\Resources;

use Mockery;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Resources\ArtistResource;
use Wimski\Beatport\Resources\ReleaseResource;
use Wimski\Beatport\Resources\TrackResource;
use Wimski\Beatport\Tests\TestCase;

class ArtistResourceTest extends TestCase
{
    /**
     * @var ArtistResource
     */
    protected $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resource = new ArtistResource();
    }

    /**
     * @test
     */
    public function it_has_a_type(): void
    {
        static::assertTrue($this->resource->type()->equals(ResourceTypeEnum::ARTIST()));
    }

    /**
     * @test
     */
    public function it_has_search_filter_genre(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::QUERY(), 'genre');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_search_filter_subgenre(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::QUERY(), 'subgenre');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
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
            ArtistResource::relationship('', 1, ReleaseResource::class),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            ArtistResource::relationshipByData($this->getDataMock(), ReleaseResource::class),
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
            ArtistResource::relationship('', 1, TrackResource::class),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            ArtistResource::relationshipByData($this->getDataMock(), TrackResource::class),
        );
    }

    /**
     * @test
     */
    public function it_can_search(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            ArtistResource::search(''),
        );
    }

    /**
     * @test
     */
    public function it_can_view(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            ArtistResource::find('', 1),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            ArtistResource::findByData($this->getDataMock()),
        );
    }

    protected function getDataMock(): DataInterface
    {
        return Mockery::mock(DataInterface::class)
            ->shouldReceive('getSlug')->once()->andReturn('')
            ->shouldReceive('getId')->once()->andReturn(1)
            ->getMock();
    }
}