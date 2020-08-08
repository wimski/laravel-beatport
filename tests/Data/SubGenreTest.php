<?php

namespace Wimski\Beatport\Tests\Data;

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
}
