<?php

namespace Wimski\Beatport\Tests\Factories;

use Wimski\Beatport\Exceptions\InvalidResourceException;
use Wimski\Beatport\Factories\ResourceFactory;
use Wimski\Beatport\Resources\ArtistResource;
use Wimski\Beatport\Resources\GenreResource;
use Wimski\Beatport\Resources\KeyResource;
use Wimski\Beatport\Resources\LabelResource;
use Wimski\Beatport\Resources\ReleaseResource;
use Wimski\Beatport\Resources\SubGenreResource;
use Wimski\Beatport\Resources\TrackResource;
use Wimski\Beatport\Tests\TestCase;

class ResourceFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider resources
     */
    public function it_makes_a_resources(string $value, string $resourceClass): void
    {
        $factory = new ResourceFactory();

        $resource = $factory->make($value);

        static::assertInstanceOf($resourceClass, $resource);
    }

    public function resources(): array
    {
        return [
            ['artist', ArtistResource::class],
            ['genre', GenreResource::class],
            ['key', KeyResource::class],
            ['label', LabelResource::class],
            ['release', ReleaseResource::class],
            ['sub-genre', SubGenreResource::class],
            ['track', TrackResource::class],
        ];
    }

    /**
     * @test
     */
    public function it_throws_an_exception_for_an_unsupported_value(): void
    {
        static::expectException(InvalidResourceException::class);
        static::expectExceptionMessage('Cannot make a resource for type foobar');

        $factory = new ResourceFactory();
        $factory->make('foobar');
    }
}
