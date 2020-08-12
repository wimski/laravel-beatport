<?php

namespace Wimski\Beatport\Tests\Processors\Resources;

use Wimski\Beatport\Data\Artist;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Processors\Resources\ArtistResourceProcessor;
use Wimski\Beatport\Tests\TestCase;

class ArtistResourceProcessorTest extends TestCase
{
    /**
     * @var ArtistResourceProcessor
     */
    protected $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->app->make(ArtistResourceProcessor::class);
    }

    /**
     * @test
     */
    public function it_processes_view_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::VIEW(),
            $this->loadHtmlStub('artist.view'),
        );

        static::assertInstanceOf(Artist::class, $data);

        /** @var Artist $data */
        static::assertSame(67136, $data->getId());
        static::assertSame('ben-nicky', $data->getSlug());
        static::assertSame('Ben Nicky', $data->getTitle());
        static::assertSame(
            'https://geo-media.beatport.com/image/fe8a1a9e-913f-41c6-85b0-704972f2e896.jpg',
            $data->getArtwork(),
        );
    }

    /**
     * @test
     */
    public function it_processes_index_data(): void
    {
        $this->itProcessesMultipleResults(RequestTypeEnum::INDEX());
    }

    /**
     * @test
     */
    public function it_processes_search_data(): void
    {
        $this->itProcessesMultipleResults(RequestTypeEnum::QUERY());
    }

    protected function itProcessesMultipleResults(RequestTypeEnum $requestType): void
    {
        $data = $this->processor->process(
            $requestType,
            $this->loadHtmlStub('artist.multiple'),
        );

        static::assertCount(2, $data);

        $artist1 = $data->get(0);
        static::assertSame(67136, $artist1->getId());
        static::assertSame('ben-nicky', $artist1->getSlug());
        static::assertSame('Ben Nicky', $artist1->getTitle());
        static::assertSame(
            'https://geo-media.beatport.com/image/fe8a1a9e-913f-41c6-85b0-704972f2e896.jpg',
            $artist1->getArtwork(),
        );

        $artist2 = $data->get(1);
        static::assertSame(642235, $artist2->getId());
        static::assertSame('ben-nicky-and-pop-art', $artist2->getSlug());
        static::assertSame('Ben Nicky & Pop Art', $artist2->getTitle());
        static::assertSame(
            'https://geo-media.beatport.com/image/0dc61986-bccf-49d4-8fad-6b147ea8f327.jpg',
            $artist2->getArtwork(),
        );
    }
}
