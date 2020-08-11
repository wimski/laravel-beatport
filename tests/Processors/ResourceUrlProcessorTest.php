<?php

namespace Wimski\Beatport\Tests\Processors;

use Illuminate\Contracts\Config\Repository;
use Mockery;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Exceptions\InvalidResourceUrlException;
use Wimski\Beatport\Processors\ResourceUrlProcessor;
use Wimski\Beatport\Tests\TestCase;

class ResourceUrlProcessorTest extends TestCase
{
    /**
     * @var ResourceUrlProcessor
     */
    protected $processor;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Repository $config */
        $config = Mockery::mock(Repository::class)
            ->shouldReceive('get')
            ->once()
            ->with('beatport.url')
            ->andReturn('http://website.com')
            ->getMock();

        $this->processor = new ResourceUrlProcessor($config);
    }

    /**
     * @test
     */
    public function it_get_resource_attributes_from_url(): void
    {
        $attributes = $this->processor->processResourceAttributes('http://website.com/track/foobar/123');

        static::assertCount(3, $attributes);
        static::assertTrue(ResourceTypeEnum::TRACK()->equals($attributes['type']));
        static::assertSame('foobar', $attributes['slug']);
        static::assertSame(123, $attributes['id']);
    }

    /**
     * @test
     * @dataProvider invalidUrls
     */
    public function it_errors_on_invalid_urls(string $url): void
    {
        static::expectException(InvalidResourceUrlException::class);
        static::expectExceptionMessage("{$url} is not a valid resource URL");

        $this->processor->processResourceAttributes($url);
    }

    public function invalidUrls(): array
    {
        return [
            ['http://website.com/track/foobar/abc'],
            ['http://website.com/track/foo+bar/123'],
            ['http://website.com/lipsum/foobar/123'],
            ['http://webstek.nl/track/foobar/123'],
        ];
    }
}
