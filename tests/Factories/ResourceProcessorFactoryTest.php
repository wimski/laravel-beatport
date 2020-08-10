<?php

namespace Wimski\Beatport\Tests\Factories;

use Illuminate\Contracts\Container\Container;
use Mockery;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Exceptions\InvalidResourceException;
use Wimski\Beatport\Factories\ResourceProcessorFactory;
use Wimski\Beatport\Processors\Resources\ArtistResourceProcessor;
use Wimski\Beatport\Processors\Resources\GenreResourceProcessor;
use Wimski\Beatport\Processors\Resources\LabelResourceProcessor;
use Wimski\Beatport\Processors\Resources\ReleaseResourceProcessor;
use Wimski\Beatport\Processors\Resources\TrackResourceProcessor;
use Wimski\Beatport\Tests\TestCase;

class ResourceProcessorFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_makes_an_artist_resource_processor(): void
    {
        $app = $this->getApp(ArtistResourceProcessor::class);

        $factory = new ResourceProcessorFactory($app);
        $factory->make(ResourceTypeEnum::ARTIST());
    }

    /**
     * @test
     */
    public function it_makes_a_genre_resource_processor(): void
    {
        $app = $this->getApp(GenreResourceProcessor::class);

        $factory = new ResourceProcessorFactory($app);
        $factory->make(ResourceTypeEnum::GENRE());
    }

    /**
     * @test
     */
    public function it_makes_a_label_resource_processor(): void
    {
        $app = $this->getApp(LabelResourceProcessor::class);

        $factory = new ResourceProcessorFactory($app);
        $factory->make(ResourceTypeEnum::LABEL());
    }

    /**
     * @test
     */
    public function it_makes_a_release_resource_processor(): void
    {
        $app = $this->getApp(ReleaseResourceProcessor::class);

        $factory = new ResourceProcessorFactory($app);
        $factory->make(ResourceTypeEnum::RELEASE());
    }

    /**
     * @test
     */
    public function it_makes_a_track_resource_processor(): void
    {
        $app = $this->getApp(TrackResourceProcessor::class);

        $factory = new ResourceProcessorFactory($app);
        $factory->make(ResourceTypeEnum::TRACK());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_for_an_unsupported_type(): void
    {
        static::expectException(InvalidResourceException::class);
        static::expectExceptionMessage('Cannot make a processor for type foobar');

        /** @var ResourceTypeEnum $enum */
        $enum = Mockery::mock(ResourceTypeEnum::class)
            ->shouldReceive('getValue')
            ->once()
            ->andReturn('foobar')
            ->getMock();

        $factory = new ResourceProcessorFactory(Mockery::mock(Container::class));
        $factory->make($enum);
    }

    protected function getApp(string $processorClass): Container
    {
        /** @var Container $app */
        $app = Mockery::mock(Container::class)
            ->shouldReceive('make')
            ->once()
            ->with($processorClass)
            ->andReturn(Mockery::mock($processorClass))
            ->getMock();

        return $app;
    }
}
