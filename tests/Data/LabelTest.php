<?php

namespace Wimski\Beatport\Tests\Data;

use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Data\Track;

/**
 * @method Label getDataObject(array $properties = null)
 */
class LabelTest extends AbstractDataTest
{
    protected function dataClass(): string
    {
        return Label::class;
    }

    /**
     * @test
     */
    public function it_has_an_artwork(): void
    {
        $object = $this->getDataObject(['artwork' => 'artwork']);
        static::assertSame('artwork', $object->getArtwork());

        $object = $this->getDataObject();
        static::assertNull($object->getArtwork());

        $object->setArtwork('artwork');
        static::assertSame('artwork', $object->getArtwork());
    }

    /**
     * @test
     */
    public function it_has_releases(): void
    {
        $release  = new Release();
        $releases = collect([$release]);

        $object = $this->getDataObject(['releases' => $releases]);
        static::assertSame($releases->toArray(), $object->getReleases()->toArray());

        $object = $this->getDataObject();
        static::assertTrue($object->getReleases()->isEmpty());

        $object->setReleases($releases);
        static::assertSame($releases->toArray(), $object->getReleases()->toArray());

        $object = $this->getDataObject();
        $object->addRelease($release);
        static::assertSame($releases->toArray(), $object->getReleases()->toArray());
    }

    /**
     * @test
     */
    public function it_has_tracks(): void
    {
        $track  = new Track();
        $tracks = collect([$track]);

        $object = $this->getDataObject(['tracks' => $tracks]);
        static::assertSame($tracks->toArray(), $object->getTracks()->toArray());

        $object = $this->getDataObject();
        static::assertTrue($object->getTracks()->isEmpty());

        $object->setTracks($tracks);
        static::assertSame($tracks->toArray(), $object->getTracks()->toArray());

        $object = $this->getDataObject();
        $object->addTrack($track);
        static::assertSame($tracks->toArray(), $object->getTracks()->toArray());
    }
}
