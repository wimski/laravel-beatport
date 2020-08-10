<?php

namespace Wimski\Beatport\Tests\Data;

use Carbon\Carbon;
use Wimski\Beatport\Data\Artist;
use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Data\SubGenre;
use Wimski\Beatport\Data\Track;

/**
 * @method Track getDataObject(array $properties = null)
 */
class TrackTest extends AbstractDataTest
{
    protected function dataClass(): string
    {
        return Track::class;
    }

    /**
     * @test
     */
    public function it_has_a_remix(): void
    {
        $object = $this->getDataObject(['remix' => 'remix']);
        static::assertSame('remix', $object->getRemix());

        $object = $this->getDataObject();
        static::assertNull($object->getRemix());

        $object->setRemix('remix');
        static::assertSame('remix', $object->getRemix());
    }

    /**
     * @test
     */
    public function it_has_a_length(): void
    {
        $object = $this->getDataObject(['length' => '2:30']);
        static::assertSame(150, $object->getLength());

        $object = $this->getDataObject();
        static::assertNull($object->getLength());

        $object->setLength('2:30');
        static::assertSame(150, $object->getLength());

        $object = $this->getDataObject(['length' => 150]);
        static::assertSame(150, $object->getLength());

        $object = $this->getDataObject();
        $object->setLength(150);
        static::assertSame(150, $object->getLength());
    }

    /**
     * @test
     */
    public function it_has_a_bpm(): void
    {
        $object = $this->getDataObject(['bpm' => 140]);
        static::assertSame(140, $object->getBpm());

        $object = $this->getDataObject();
        static::assertNull($object->getBpm());

        $object->setBpm(140);
        static::assertSame(140, $object->getBpm());
    }

    /**
     * @test
     */
    public function it_has_a_key(): void
    {
        $object = $this->getDataObject(['key' => 'key']);
        static::assertSame('key', $object->getKey());

        $object = $this->getDataObject();
        static::assertNull($object->getKey());

        $object->setKey('key');
        static::assertSame('key', $object->getKey());
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
    public function it_has_a_genre(): void
    {
        $genre = new Genre();

        $object = $this->getDataObject(['genre' => $genre]);
        static::assertSame($genre, $object->getGenre());

        $object = $this->getDataObject();
        static::assertNull($object->getGenre());

        $object->setGenre($genre);
        static::assertSame($genre, $object->getGenre());
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
    public function it_has_a_release(): void
    {
        $release = new Release();

        $object = $this->getDataObject(['release' => $release]);
        static::assertSame($release, $object->getRelease());

        $object = $this->getDataObject();
        static::assertNull($object->getRelease());

        $object->setRelease($release);
        static::assertSame($release, $object->getRelease());
    }

    /**
     * @test
     */
    public function it_has_a_sub_genre(): void
    {
        $subGenre = new SubGenre();

        $object = $this->getDataObject(['subGenre' => $subGenre]);
        static::assertSame($subGenre, $object->getSubGenre());

        $object = $this->getDataObject();
        static::assertNull($object->getSubGenre());

        $object->setSubGenre($subGenre);
        static::assertSame($subGenre, $object->getSubGenre());
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
    public function it_has_remixers(): void
    {
        $remixer  = new Artist();
        $remixers = collect([$remixer]);

        $object = $this->getDataObject(['remixers' => $remixers]);
        static::assertSame($remixers->toArray(), $object->getRemixers()->toArray());

        $object = $this->getDataObject();
        static::assertTrue($object->getRemixers()->isEmpty());

        $object->setRemixers($remixers);
        static::assertSame($remixers->toArray(), $object->getRemixers()->toArray());

        $object = $this->getDataObject();
        $object->addRemixer($remixer);
        static::assertSame($remixers->toArray(), $object->getRemixers()->toArray());
    }
}
