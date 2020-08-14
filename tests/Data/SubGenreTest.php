<?php

namespace Wimski\Beatport\Tests\Data;

use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Data\SubGenre;

/**
 * @method SubGenre getDataObject(array $properties = null)
 */
class SubGenreTest extends AbstractDataTest
{
    protected function dataClass(): string
    {
        return SubGenre::class;
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
}
