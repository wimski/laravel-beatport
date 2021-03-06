<?php

namespace Wimski\Beatport\Tests\Resources;

use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Resources\LabelResource;
use Wimski\Beatport\Resources\ReleaseResource;
use Wimski\Beatport\Resources\TrackResource;

class LabelSharedResourceTest extends AbstractSharedResourceTest
{
    protected function resourceClass(): string
    {
        return LabelResource::class;
    }

    /**
     * @test
     */
    public function it_has_a_type(): void
    {
        static::assertTrue($this->resource->type()->equals(ResourceTypeEnum::LABEL()));
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
            LabelResource::relationship('', 1, ReleaseResource::class),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            LabelResource::relationshipByData($this->getDataMock(), ReleaseResource::class),
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
            LabelResource::relationship('', 1, TrackResource::class),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            LabelResource::relationshipByData($this->getDataMock(), TrackResource::class),
        );
    }

    /**
     * @test
     */
    public function it_can_search(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            LabelResource::search(''),
        );
    }

    /**
     * @test
     */
    public function it_can_view(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            LabelResource::find('', 1),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            LabelResource::findByData($this->getDataMock()),
        );
    }
}
