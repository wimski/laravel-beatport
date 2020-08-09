<?php

namespace Wimski\Beatport\Tests\Factories;

use Illuminate\Contracts\Container\Container;
use Mockery;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Exceptions\InvalidResourceException;
use Wimski\Beatport\Factories\ResourceProcessorFactory;
use Wimski\Beatport\Processors\Resources\ArtistResourceProcessor;
use Wimski\Beatport\Processors\Resources\GenreResourceProcessor;
use Wimski\Beatport\Processors\Resources\LabelResourceProcessor;
use Wimski\Beatport\Processors\Resources\ReleaseResourceProcessor;
use Wimski\Beatport\Processors\Resources\TrackResourceProcessor;
use Wimski\Beatport\Resources\ArtistResource;
use Wimski\Beatport\Resources\GenreResource;
use Wimski\Beatport\Resources\LabelResource;
use Wimski\Beatport\Resources\ReleaseResource;
use Wimski\Beatport\Resources\TrackResource;
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
        $factory->make(new ArtistResource());
    }

    /**
     * @test
     */
    public function it_makes_a_genre_resource_processor(): void
    {
        $app = $this->getApp(GenreResourceProcessor::class);

        $factory = new ResourceProcessorFactory($app);
        $factory->make(new GenreResource());
    }

    /**
     * @test
     */
    public function it_makes_a_label_resource_processor(): void
    {
        $app = $this->getApp(LabelResourceProcessor::class);

        $factory = new ResourceProcessorFactory($app);
        $factory->make(new LabelResource());
    }

    /**
     * @test
     */
    public function it_makes_a_release_resource_processor(): void
    {
        $app = $this->getApp(ReleaseResourceProcessor::class);

        $factory = new ResourceProcessorFactory($app);
        $factory->make(new ReleaseResource());
    }

    /**
     * @test
     */
    public function it_makes_a_track_resource_processor(): void
    {
        $app = $this->getApp(TrackResourceProcessor::class);

        $factory = new ResourceProcessorFactory($app);
        $factory->make(new TrackResource());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_for_an_unsupported_type(): void
    {
        static::expectException(InvalidResourceException::class);
        static::expectExceptionMessage('Cannot make a processor for foobar resource');

        $app = Mockery::mock(Container::class);

        $enum = Mockery::mock(ResourceTypeEnum::class)
            ->shouldReceive('getValue')
            ->once()
            ->andReturn('foobar')
            ->getMock();

        /** @var ResourceInterface $resource */
        $resource = Mockery::mock(ResourceInterface::class)
            ->shouldReceive('type')
            ->once()
            ->andReturn($enum)
            ->getMock();

        $factory = new ResourceProcessorFactory($app);
        $factory->make($resource);
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
