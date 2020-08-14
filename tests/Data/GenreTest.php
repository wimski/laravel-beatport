<?php

namespace Wimski\Beatport\Tests\Data;

use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Data\SubGenre;
use Wimski\Beatport\Data\Track;

/**
 * @method Genre getDataObject(array $properties = null)
 */
class GenreTest extends AbstractDataTest
{
    protected function dataClass(): string
    {
        return Genre::class;
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
    public function it_has_sub_genres(): void
    {
        $subGenre  = new SubGenre();
        $subGenres = collect([$subGenre]);

        $object = $this->getDataObject(['subGenres' => $subGenres]);
        static::assertSame($subGenres->toArray(), $object->getSubGenres()->toArray());

        $object = $this->getDataObject();
        static::assertTrue($object->getSubGenres()->isEmpty());

        $object->setSubGenres($subGenres);
        static::assertSame($subGenres->toArray(), $object->getSubGenres()->toArray());

        $object = $this->getDataObject();
        $object->addSubGenre($subGenre);
        static::assertSame($subGenres->toArray(), $object->getSubGenres()->toArray());
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
