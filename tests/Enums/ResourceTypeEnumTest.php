<?php

namespace Wimski\Beatport\Tests\Enums;

use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Tests\TestCase;

class ResourceTypeEnumTest extends TestCase
{
    /**
     * @test
     * @dataProvider resourceTypes
     */
    public function it_returns_a_plural_value(string $value, string $plural): void
    {
        $enum = new ResourceTypeEnum($value);

        static::assertSame($plural, $enum->getValuePlural());
    }

    public function resourceTypes(): array
    {
        return [
            ['artist',    'artists'],
            ['key',       'keys'],
            ['genre',     'genres'],
            ['label',     'labels'],
            ['release',   'releases'],
            ['sub-genre', 'sub-genres'],
            ['track',     'tracks'],
        ];
    }
}
