<?php

namespace Wimski\Beatport\Tests\Data;

use Wimski\Beatport\Data\Key;

/**
 * @method Key getDataObject(array $properties = null)
 */
class KeyTest extends AbstractDataTest
{
    protected function dataClass(): string
    {
        return Key::class;
    }
}
