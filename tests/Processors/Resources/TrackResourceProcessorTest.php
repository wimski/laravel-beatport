<?php

namespace Wimski\Beatport\Tests\Processors\Resources;

use Carbon\Carbon;
use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Data\SubGenre;
use Wimski\Beatport\Data\Track;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Processors\Resources\TrackResourceProcessor;
use Wimski\Beatport\Tests\TestCase;

class TrackResourceProcessorTest extends TestCase
{
    /**
     * @var TrackResourceProcessor
     */
    protected $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->app->make(TrackResourceProcessor::class);
    }

    /**
     * @test
     */
    public function it_processes_view_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::VIEW(),
            $this->loadHtmlStub('track.view'),
        );

        static::assertInstanceOf(Track::class, $data);

        /** @var Track $data */
        static::assertSame(14023799, $data->getId());
        static::assertSame('raindrops-sunset-bros-extended-remix', $data->getSlug());
        static::assertSame('Raindrops', $data->getTitle());
        static::assertSame('Sunset Bros Extended Remix', $data->getRemix());
        static::assertSame(299, $data->getLength());
        static::assertTrue(Carbon::create(2020, 7, 31)->equalTo($data->getReleased()));
        static::assertSame(145, $data->getBpm());
        static::assertSame('B min', $data->getKey());
        static::assertSame(
            'https://geo-media.beatport.com/image/6e2bd691-2ab6-433b-8f2a-81744b12836d.png',
            $data->getWaveform(),
        );

        $genre = $data->getGenre();
        static::assertInstanceOf(Genre::class, $genre);
        static::assertSame(8, $genre->getId());
        static::assertSame('hard-dance-hardcore', $genre->getSlug());
        static::assertSame('Hard Dance / Hardcore', $genre->getTitle());

        $subGenre = $data->getSubGenre();
        static::assertInstanceOf(SubGenre::class, $subGenre);
        static::assertSame(121, $subGenre->getId());
        static::assertSame('hard-trance', $subGenre->getSlug());
        static::assertSame('Hard Trance', $subGenre->getTitle());

        $label = $data->getLabel();
        static::assertInstanceOf(Label::class, $label);
        static::assertSame(88162, $label->getId());
        static::assertSame('xploded-music-limited', $label->getSlug());
        static::assertSame('Xploded Music Limited', $label->getTitle());

        $release = $data->getRelease();
        static::assertInstanceOf(Release::class, $release);
        static::assertSame(3075573, $release->getId());
        static::assertSame('raindrops', $release->getSlug());
        static::assertSame('Raindrops', $release->getTitle());
        static::assertSame(
            'https://geo-media.beatport.com/image/dd75f45e-8cfa-43fb-932b-468af32a80fc.jpg',
            $release->getArtwork(),
        );

        $artists = $data->getArtists();
        static::assertCount(2, $artists);

        $artist1 = $artists->get(0);
        static::assertSame(13939, $artist1->getId());
        static::assertSame('stunt', $artist1->getSlug());
        static::assertSame('Stunt', $artist1->getTitle());

        $artist2 = $artists->get(1);
        static::assertSame(67136, $artist2->getId());
        static::assertSame('ben-nicky', $artist2->getSlug());
        static::assertSame('Ben Nicky', $artist2->getTitle());

        $remixers = $data->getRemixers();
        static::assertCount(1, $remixers);

        $remixer = $remixers->get(0);
        static::assertSame(116822, $remixer->getId());
        static::assertSame('sunset-bros', $remixer->getSlug());
        static::assertSame('Sunset Bros', $remixer->getTitle());
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
    public function it_processes_relationship_data(): void
    {
        $this->itProcessesMultipleResults(RequestTypeEnum::RELATIONSHIP());
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
            $this->loadHtmlStub('track.multiple'),
        );

        static::assertCount(2, $data);

        $track1 = $data->get(0);
        static::assertSame(13922362, $track1->getId());
        static::assertSame('destruction-original-mix', $track1->getSlug());
        static::assertSame('Destruction', $track1->getTitle());
        static::assertSame('Original Mix', $track1->getRemix());
        static::assertTrue(Carbon::create(2020, 7, 31)->equalTo($track1->getReleased()));
        static::assertSame('F♯ maj', $track1->getKey());

        $genre = $track1->getGenre();
        static::assertInstanceOf(Genre::class, $genre);
        static::assertSame(8, $genre->getId());
        static::assertSame('hard-dance-hardcore', $genre->getSlug());
        static::assertSame('Hard Dance / Hardcore', $genre->getTitle());

        $label = $track1->getLabel();
        static::assertInstanceOf(Label::class, $label);
        static::assertSame(88111, $label->getId());
        static::assertSame('epidemic-digital', $label->getSlug());
        static::assertSame('Epidemic Digital', $label->getTitle());

        $release = $track1->getRelease();
        static::assertInstanceOf(Release::class, $release);
        static::assertSame(3048827, $release->getId());
        static::assertSame('destruction', $release->getSlug());
        static::assertSame(
            'https://geo-media.beatport.com/image_size/95x95/29f0b48d-61d9-4378-a8ac-66c083536c17.jpg',
            $release->getArtwork(),
        );

        $artists = $track1->getArtists();
        static::assertCount(1, $artists);

        $artist1 = $artists->get(0);
        static::assertSame(322298, $artist1->getId());
        static::assertSame('noath', $artist1->getSlug());
        static::assertSame('Noath', $artist1->getTitle());

        $track2 = $data->get(1);
        static::assertSame(14023799, $track2->getId());
        static::assertSame('raindrops-sunset-bros-extended-remix', $track2->getSlug());
        static::assertSame('Raindrops', $track2->getTitle());
        static::assertSame('Sunset Bros Extended Remix', $track2->getRemix());
        static::assertTrue(Carbon::create(2020, 7, 31)->equalTo($track2->getReleased()));
        static::assertSame('B min', $track2->getKey());

        $subGenre = $track2->getSubGenre();
        static::assertInstanceOf(SubGenre::class, $subGenre);
        static::assertSame(121, $subGenre->getId());
        static::assertSame('hard-trance', $subGenre->getSlug());
        static::assertSame('Hard Trance', $subGenre->getTitle());

        $label = $track2->getLabel();
        static::assertInstanceOf(Label::class, $label);
        static::assertSame(88162, $label->getId());
        static::assertSame('xploded-music-limited', $label->getSlug());
        static::assertSame('Xploded Music Limited', $label->getTitle());

        $release = $track2->getRelease();
        static::assertInstanceOf(Release::class, $release);
        static::assertSame(3075573, $release->getId());
        static::assertSame('raindrops', $release->getSlug());
        static::assertSame(
            'https://geo-media.beatport.com/image_size/95x95/dd75f45e-8cfa-43fb-932b-468af32a80fc.jpg',
            $release->getArtwork(),
        );

        $artists = $track2->getArtists();
        static::assertCount(2, $artists);

        $artist1 = $artists->get(0);
        static::assertSame(13939, $artist1->getId());
        static::assertSame('stunt', $artist1->getSlug());
        static::assertSame('Stunt', $artist1->getTitle());

        $artist2 = $artists->get(1);
        static::assertSame(67136, $artist2->getId());
        static::assertSame('ben-nicky', $artist2->getSlug());
        static::assertSame('Ben Nicky', $artist2->getTitle());

        $remixers = $track2->getRemixers();
        static::assertCount(1, $remixers);

        $remixer = $remixers->get(0);
        static::assertSame(116822, $remixer->getId());
        static::assertSame('sunset-bros', $remixer->getSlug());
        static::assertSame('Sunset Bros', $remixer->getTitle());
    }
}
