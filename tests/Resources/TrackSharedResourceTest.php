<?php

namespace Wimski\Beatport\Tests\Resources;

use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Filters\BpmFilter;
use Wimski\Beatport\Requests\Filters\DateFilter;
use Wimski\Beatport\Requests\Filters\PreorderFilter;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Requests\Filters\TypeFilter;
use Wimski\Beatport\Resources\TrackResource;

class TrackSharedResourceTest extends AbstractSharedResourceTest
{
    protected function resourceClass(): string
    {
        return TrackResource::class;
    }

    /**
     * @test
     */
    public function it_has_a_type(): void
    {
        static::assertTrue($this->resource->type()->equals(ResourceTypeEnum::TRACK()));
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
    public function it_has_index_filter_bpm(): void
    {
        static::assertInstanceOf(
            BpmFilter::class,
            $this->resource->getFilter(RequestTypeEnum::INDEX(), 'bpm'),
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
        static::assertFalse($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_index_filter_key(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::INDEX(), 'key');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertFalse($filter->supportsMultipleValues());
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
        static::assertFalse($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_relationship_filter_bpm(): void
    {
        static::assertInstanceOf(
            BpmFilter::class,
            $this->resource->getFilter(RequestTypeEnum::RELATIONSHIP(), 'bpm'),
        );
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
        static::assertFalse($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_relationship_filter_key(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::RELATIONSHIP(), 'key');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertFalse($filter->supportsMultipleValues());
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
        static::assertFalse($filter->supportsMultipleValues());
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
        static::assertFalse($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_search_filter_key(): void
    {
        $filter = $this->resource->getFilter(RequestTypeEnum::QUERY(), 'key');

        static::assertNotNull($filter);
        static::assertInstanceOf(ResourceFilter::class, $filter);

        /** @var ResourceFilter $filter */
        static::assertFalse($filter->supportsMultipleValues());
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
        static::assertFalse($filter->supportsMultipleValues());
    }

    /**
     * @test
     */
    public function it_has_index_sort_genre(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::INDEX(), 'genre'),
        );
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
    public function it_has_relationship_sort_genre(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::RELATIONSHIP(), 'genre'),
        );
    }

    /**
     * @test
     */
    public function it_has_relationship_sort_label(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::RELATIONSHIP(), 'label'),
        );
    }

    /**
     * @test
     */
    public function it_has_relationship_sort_release(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::RELATIONSHIP(), 'release'),
        );
    }

    /**
     * @test
     */
    public function it_has_relationship_sort_title(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::RELATIONSHIP(), 'title'),
        );
    }

    /**
     * @test
     */
    public function it_has_search_sort_genre(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::QUERY(), 'genre'),
        );
    }

    /**
     * @test
     */
    public function it_has_search_sort_label(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::QUERY(), 'label'),
        );
    }

    /**
     * @test
     */
    public function it_has_search_sort_release(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::QUERY(), 'release'),
        );
    }

    /**
     * @test
     */
    public function it_has_search_sort_title(): void
    {
        static::assertInstanceOf(
            RequestSortInterface::class,
            $this->resource->getSort(RequestTypeEnum::QUERY(), 'title'),
        );
    }

    /**
     * @test
     */
    public function it_can_index(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            TrackResource::all(),
        );
    }

    /**
     * @test
     */
    public function it_can_search(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            TrackResource::search(''),
        );
    }

    /**
     * @test
     */
    public function it_can_view(): void
    {
        static::assertInstanceOf(
            RequestBuilderInterface::class,
            TrackResource::find('', 1),
        );

        static::assertInstanceOf(
            RequestBuilderInterface::class,
            TrackResource::findByData($this->getDataMock()),
        );
    }
}
