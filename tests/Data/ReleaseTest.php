<?php

namespace Wimski\Beatport\Tests\Data;

use Carbon\Carbon;
use Wimski\Beatport\Data\Artist;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Data\Track;

/**
 * @method Release getDataObject(array $properties = null)
 */
class ReleaseTest extends AbstractDataTest
{
    protected function dataClass(): string
    {
        return Release::class;
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
    public function it_has_a_description(): void
    {
        $object = $this->getDataObject(['description' => 'description']);
        static::assertSame('description', $object->getDescription());

        $object = $this->getDataObject();
        static::assertNull($object->getDescription());

        $object->setDescription('description');
        static::assertSame('description', $object->getDescription());
    }

    /**
     * @test
     */
    public function it_has_a_catalog(): void
    {
        $object = $this->getDataObject(['catalog' => 'catalog']);
        static::assertSame('catalog', $object->getCatalog());

        $object = $this->getDataObject();
        static::assertNull($object->getCatalog());

        $object->setCatalog('catalog');
        static::assertSame('catalog', $object->getCatalog());
    }

    /**
     * @test
     */
    public function it_has_a_released(): void
    {
        $date = Carbon::create(2020, 8, 1);

        $object = $this->getDataObject(['released' => '2020-08-01']);
        static::assertTrue($object->getReleased()->equalTo($date));

        $object = $this->getDataObject();
        static::assertNull($object->getReleased());

        $object->setReleased('2020-08-01');
        static::assertTrue($object->getReleased()->equalTo($date));

        $object = $this->getDataObject(['released' => $date]);
        static::assertTrue($object->getReleased()->equalTo($date));

        $object = $this->getDataObject();
        $object->setReleased($date);
        static::assertTrue($object->getReleased()->equalTo($date));
    }

    /**
     * @test
     */
    public function it_has_a_label(): void
    {
        $label = new Label();

        $object = $this->getDataObject(['label' => $label]);
        static::assertSame($label, $object->getLabel());

        $object = $this->getDataObject();
        static::assertNull($object->getLabel());

        $object->setLabel($label);
        static::assertSame($label, $object->getLabel());
    }

    /**
     * @test
     */
    public function it_has_artists(): void
    {
        $artist  = new Artist();
        $artists = collect([$artist]);

        $object = $this->getDataObject(['artists' => $artists]);
        static::assertSame($artists->toArray(), $object->getArtists()->toArray());

        $object = $this->getDataObject();
        static::assertTrue($object->getArtists()->isEmpty());

        $object->setArtists($artists);
        static::assertSame($artists->toArray(), $object->getArtists()->toArray());

        $object = $this->getDataObject();
        $object->addArtist($artist);
        static::assertSame($artists->toArray(), $object->getArtists()->toArray());
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
