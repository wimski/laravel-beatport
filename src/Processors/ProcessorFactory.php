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
        switch ($resource->type()) {
            case ResourceTypeEnum::TRACK:
                return $this->app->make(TrackProcessor::class);

            default:
                // throw new Exception
        }
    }
}
