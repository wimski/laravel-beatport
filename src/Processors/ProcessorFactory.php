<?php

namespace Wimski\Beatport\Processors;

use Illuminate\Contracts\Container\Container;
use Wimski\Beatport\Contracts\ProcessorFactoryInterface;
use Wimski\Beatport\Contracts\ProcessorInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;

class ProcessorFactory implements ProcessorFactoryInterface
{
    /**
     * @var Container
     */
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function make(ResourceInterface $resource): ProcessorInterface
    {
        switch ($resource->type()->getValue()) {
            case ResourceTypeEnum::ARTIST:
                return $this->app->make(ArtistProcessor::class);

            case ResourceTypeEnum::GENRE:
                return $this->app->make(GenreProcessor::class);

            case ResourceTypeEnum::LABEL:
                return $this->app->make(LabelProcessor::class);

            case ResourceTypeEnum::RELEASE:
                return $this->app->make(ReleaseProcessor::class);

            case ResourceTypeEnum::TRACK:
                return $this->app->make(TrackProcessor::class);

            default:
                // throw new Exception
        }
    }
}
