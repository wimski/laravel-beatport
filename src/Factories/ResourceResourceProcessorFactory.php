<?php

namespace Wimski\Beatport\Factories;

use Illuminate\Contracts\Container\Container;
use Wimski\Beatport\Contracts\ResourceProcessorFactoryInterface;
use Wimski\Beatport\Contracts\ResourceProcessorInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Processors\Resources\ArtistResourceProcessor;
use Wimski\Beatport\Processors\Resources\GenreResourceProcessor;
use Wimski\Beatport\Processors\Resources\LabelResourceProcessor;
use Wimski\Beatport\Processors\Resources\ReleaseResourceProcessor;
use Wimski\Beatport\Processors\Resources\TrackResourceProcessor;

class ResourceResourceProcessorFactory implements ResourceProcessorFactoryInterface
{
    /**
     * @var Container
     */
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function make(ResourceInterface $resource): ResourceProcessorInterface
    {
        switch ($resource->type()->getValue()) {
            case ResourceTypeEnum::ARTIST:
                return $this->app->make(ArtistResourceProcessor::class);

            case ResourceTypeEnum::GENRE:
                return $this->app->make(GenreResourceProcessor::class);

            case ResourceTypeEnum::LABEL:
                return $this->app->make(LabelResourceProcessor::class);

            case ResourceTypeEnum::RELEASE:
                return $this->app->make(ReleaseResourceProcessor::class);

            case ResourceTypeEnum::TRACK:
                return $this->app->make(TrackResourceProcessor::class);

            default:
                // throw new Exception
        }
    }
}
