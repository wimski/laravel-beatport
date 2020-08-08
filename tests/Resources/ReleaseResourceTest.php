<?php

namespace Wimski\Beatport\Tests\Resources;

use Mockery;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\DateFilter;
use Wimski\Beatport\Requests\Filters\PreorderFilter;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Requests\Filters\TypeFilter;
use Wimski\Beatport\Resources\ReleaseResource;
use Wimski\Beatport\Tests\TestCase;

class ReleaseResourceTest extends TestCase
{
    /**
     * @var ReleaseResource
     */
    protected $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resource = new ReleaseResource();
    }

    /**
     * @test
     */
    public function it_has_a_type(): void
    {
        static::assertTrue($this->resource->type()->equals(ResourceTypeEnum::RELEASE()));
    }

    /**
     * @test
     */
    public function it_has_index_filter_preorder(): void
    {
        static::assertInstanceOf(
            PreorderFilter::class,
            $this->resource->getFilter(RequestTypeEnum::INDEX(), 'preorder'),
        );
    }

    /**
     * @test
     */
    public function it_has_index_filter_date(): void
    {
        static::assertInstanceOf(
            DateFilter::class,
            $this->resource->getFilter(RequestTypeEnum::INDEX(), 'date'),
        );
    }

    /**
     * @test
     */
    public function it_has_index_filter_type(): void
    {
        static::assertInstanceOf(
            TypeFilter::class,
            $this->resource->getFilter(RequestTypeEnum::INDEX(), 'type'),
        );
    }

    /**
     * @test
     */
    public function it_has_index_filter_artists(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::INDEX(), 'artists');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_index_filter_genres(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::INDEX(), 'genres');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_index_filter_label(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::INDEX(), 'label');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertFalse($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_index_filter_subgenre(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::INDEX(), 'subgenre');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_relationship_filter_date(): void
    {
        static::assertInstanceOf(
            DateFilter::class,
            $this->resource->getFilter(RequestTypeEnum::RELATIONSHIP(), 'date'),
        );
    }

    /**
     * @test
     */
    public function it_has_relationship_filter_type(): void
    {
        static::assertInstanceOf(
            TypeFilter::class,
            $this->resource->getFilter(RequestTypeEnum::RELATIONSHIP(), 'type'),
        );
    }

    /**
     * @test
     */
    public function it_has_relationship_filter_artists(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::RELATIONSHIP(), 'artists');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_relationship_filter_genres(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::RELATIONSHIP(), 'genres');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_relationship_filter_label(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::RELATIONSHIP(), 'label');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertFalse($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_relationship_filter_subgenre(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::RELATIONSHIP(), 'subgenre');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_search_filter_artists(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::QUERY(), 'artists');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_search_filter_genres(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::QUERY(), 'genres');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertTrue($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_search_filter_label(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::QUERY(), 'label');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertFalse($filter->supportsMultipleValues());
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
    public function it_has_index_sort_label(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::INDEX(), 'label'),
        );
    }

    /**
     * @test
     */
    public function it_has_index_sort_release(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::INDEX(), 'release'),
        );
    }

    /**
     * @test
     */
    public function it_has_index_sort_title(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::INDEX(), 'title'),
        );
    }

    /**
     * @test
     */
    public function it_can_index(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            ReleaseResource::all(),
        );
    }

    /**
     * @test
     */
    public function it_can_search(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            ReleaseResource::search(''),
        );
    }

    /**
     * @test
     */
    public function it_can_view(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            ReleaseResource::find('', 1),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            ReleaseResource::findByData($this->getDataMock()),
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
