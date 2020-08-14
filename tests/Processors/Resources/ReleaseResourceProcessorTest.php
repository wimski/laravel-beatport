<?php

namespace Wimski\Beatport\Tests\Processors\Resources;

use Carbon\Carbon;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Data\SubGenre;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Processors\Resources\ReleaseResourceProcessor;
use Wimski\Beatport\Tests\TestCase;

class ReleaseResourceProcessorTest extends TestCase
{
    /**
     * @var ReleaseResourceProcessor
     */
    protected $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->app->make(ReleaseResourceProcessor::class);
    }

    /**
     * @test
     */
    public function it_processes_view_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::VIEW(),
            $this->loadHtmlStub('release.view'),
        );

        static::assertInstanceOf(Release::class, $data);

        /** @var Release $data */
        static::assertSame(3075573, $data->getId());
        static::assertSame('raindrops', $data->getSlug());
        static::assertSame('Raindrops', $data->getTitle());
        static::assertTrue(Carbon::create(2020, 7, 31)->equalTo($data->getReleased()));
        static::assertSame('XPLODED033BB', $data->getCatalog());
        static::assertSame('Lorem ipsum dolor sit amet', $data->getDescription());
        static::assertSame(
            'https://geo-media.beatport.com/image/dd75f45e-8cfa-43fb-932b-468af32a80fc.jpg',
            $data->getArtwork(),
        );

        $label = $data->getLabel();
        static::assertInstanceOf(Label::class, $label);
        static::assertSame(88162, $label->getId());
        static::assertSame('xploded-music-limited', $label->getSlug());
        static::assertSame('Xploded Music Limited', $label->getTitle());

        $artists = $data->getArtists();
        static::assertCount(3, $artists);

        $artist1 = $artists->get(0);
        static::assertSame(13939, $artist1->getId());
        static::assertSame('stunt', $artist1->getSlug());
        static::assertSame('Stunt', $artist1->getTitle());

        $artist2 = $artists->get(1);
        static::assertSame(67136, $artist2->getId());
        static::assertSame('ben-nicky', $artist2->getSlug());
        static::assertSame('Ben Nicky', $artist2->getTitle());

        $artist3 = $artists->get(2);
        static::assertSame(116822, $artist3->getId());
        static::assertSame('sunset-bros', $artist3->getSlug());
        static::assertSame('Sunset Bros', $artist3->getTitle());

        $tracks = $data->getTracks();
        static::assertCount(1, $tracks);

        $track = $tracks->get(0);
        static::assertSame('raindrops-sunset-bros-extended-remix', $track->getSlug());
        static::assertSame('Raindrops', $track->getTitle());
        static::assertSame('Sunset Bros Extended Remix', $track->getRemix());
        static::assertSame(299, $track->getLength());
        static::assertSame(145, $track->getBpm());
        static::assertSame('B min', $track->getKey());
        static::assertSame($data, $track->getRelease());
        static::assertSame($data->getReleased(), $track->getReleased());
        static::assertSame($data->getLabel(), $track->getLabel());

        $subGenre = $track->getSubGenre();
        static::assertInstanceOf(SubGenre::class, $subGenre);
        static::assertSame(121, $subGenre->getId());
        static::assertSame('hard-trance', $subGenre->getSlug());
        static::assertSame('Hard Trance', $subGenre->getTitle());

        $trackArtists = $track->getArtists();
        static::assertCount(2, $trackArtists);

        $trackArtist1 = $trackArtists->get(0);
        static::assertSame(13939, $trackArtist1->getId());
        static::assertSame('stunt', $trackArtist1->getSlug());
        static::assertSame('Stunt', $trackArtist1->getTitle());

        $trackArtist2 = $trackArtists->get(1);
        static::assertSame(67136, $trackArtist2->getId());
        static::assertSame('ben-nicky', $trackArtist2->getSlug());
        static::assertSame('Ben Nicky', $trackArtist2->getTitle());

        $remixers = $track->getRemixers();
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

    protected function itProcessesMultipleResults(RequestTypeEnum $requestType): void
    {
        $data = $this->processor->process(
            $requestType,
            $this->loadHtmlStub('release.multiple'),
        );

        static::assertCount(2, $data);

        $release1 = $data->get(0);
        static::assertSame(3048827, $release1->getId());
        static::assertSame('destruction', $release1->getSlug());
        static::assertSame('Destruction', $release1->getTitle());
        static::assertTrue(Carbon::create(2020, 7, 31)->equalTo($release1->getReleased()));
        static::assertSame(
            'https://geo-media.beatport.com/image_size/250x250/29f0b48d-61d9-4378-a8ac-66c083536c17.jpg',
            $release1->getArtwork(),
        );

        $label1 = $release1->getLabel();
        static::assertInstanceOf(Label::class, $label1);
        static::assertSame(88111, $label1->getId());
        static::assertSame('epidemic-digital', $label1->getSlug());
        static::assertSame('Epidemic Digital', $label1->getTitle());

        $artists1 = $release1->getArtists();
        static::assertCount(1, $artists1);

        $artist1_1 = $artists1->get(0);
        static::assertSame(322298, $artist1_1->getId());
        static::assertSame('noath', $artist1_1->getSlug());
        static::assertSame('Noath', $artist1_1->getTitle());

        $release2 = $data->get(1);
        static::assertSame(3075573, $release2->getId());
        static::assertSame('raindrops', $release2->getSlug());
        static::assertSame('Raindrops', $release2->getTitle());
        static::assertTrue(Carbon::create(2020, 7, 31)->equalTo($release2->getReleased()));
        static::assertSame(
            'https://geo-media.beatport.com/image_size/250x250/dd75f45e-8cfa-43fb-932b-468af32a80fc.jpg',
            $release2->getArtwork(),
        );

        $label2 = $release2->getLabel();
        static::assertInstanceOf(Label::class, $label2);
        static::assertSame(88162, $label2->getId());
        static::assertSame('xploded-music-limited', $label2->getSlug());
        static::assertSame('Xploded Music Limited', $label2->getTitle());

        $artists2 = $release2->getArtists();
        static::assertCount(3, $artists2);

        $artist2_1 = $artists2->get(0);
        static::assertSame(116822, $artist2_1->getId());
        static::assertSame('sunset-bros', $artist2_1->getSlug());
        static::assertSame('Sunset Bros', $artist2_1->getTitle());

        $artist2_2 = $artists2->get(1);
        static::assertSame(13939, $artist2_2->getId());
        static::assertSame('stunt', $artist2_2->getSlug());
        static::assertSame('Stunt', $artist2_2->getTitle());

        $artist2_3 = $artists2->get(2);
        static::assertSame(67136, $artist2_3->getId());
        static::assertSame('ben-nicky', $artist2_3->getSlug());
        static::assertSame('Ben Nicky', $artist2_3->getTitle());
    }

    /**
     * @test
     */
    public function it_processes_search_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::QUERY(),
            $this->loadHtmlStub('release.search'),
        );

        static::assertCount(2, $data);

        $release1 = $data->get(0);
        static::assertSame(3048827, $release1->getId());
        static::assertSame('destruction', $release1->getSlug());
        static::assertSame('Destruction', $release1->getTitle());
        static::assertSame(
            'https://geo-media.beatport.com/image_size/250x250/29f0b48d-61d9-4378-a8ac-66c083536c17.jpg',
            $release1->getArtwork(),
        );

        $label1 = $release1->getLabel();
        static::assertInstanceOf(Label::class, $label1);
        static::assertSame(88111, $label1->getId());
        static::assertSame('epidemic-digital', $label1->getSlug());
        static::assertSame('Epidemic Digital', $label1->getTitle());

        $artists1 = $release1->getArtists();
        static::assertCount(1, $artists1);

        $artist1_1 = $artists1->get(0);
        static::assertSame(322298, $artist1_1->getId());
        static::assertSame('noath', $artist1_1->getSlug());
        static::assertSame('Noath', $artist1_1->getTitle());

        $release2 = $data->get(1);
        static::assertSame(3075573, $release2->getId());
        static::assertSame('raindrops', $release2->getSlug());
        static::assertSame('Raindrops', $release2->getTitle());
        static::assertSame(
            'https://geo-media.beatport.com/image_size/250x250/dd75f45e-8cfa-43fb-932b-468af32a80fc.jpg',
            $release2->getArtwork(),
        );

        $label2 = $release2->getLabel();
        static::assertInstanceOf(Label::class, $label2);
        static::assertSame(88162, $label2->getId());
        static::assertSame('xploded-music-limited', $label2->getSlug());
        static::assertSame('Xploded Music Limited', $label2->getTitle());

        $artists2 = $release2->getArtists();
        static::assertCount(3, $artists2);

        $artist2_1 = $artists2->get(0);
        static::assertSame(116822, $artist2_1->getId());
        static::assertSame('sunset-bros', $artist2_1->getSlug());
        static::assertSame('Sunset Bros', $artist2_1->getTitle());

        $artist2_2 = $artists2->get(1);
        static::assertSame(13939, $artist2_2->getId());
        static::assertSame('stunt', $artist2_2->getSlug());
        static::assertSame('Stunt', $artist2_2->getTitle());

        $artist2_3 = $artists2->get(2);
        static::assertSame(67136, $artist2_3->getId());
        static::assertSame('ben-nicky', $artist2_3->getSlug());
        static::assertSame('Ben Nicky', $artist2_3->getTitle());
    }

    /**
     * @test
     */
    public function it_does_not_process_missing_data(): void
    {
        static::assertNull($this->processor->process(
            RequestTypeEnum::INDEX(),
            $this->loadHtmlStub('empty'),
        ));

        static::assertNull($this->processor->process(
            RequestTypeEnum::RELATIONSHIP(),
            $this->loadHtmlStub('empty'),
        ));

        static::assertNull($this->processor->process(
            RequestTypeEnum::QUERY(),
            $this->loadHtmlStub('empty'),
        ));
    }
}
