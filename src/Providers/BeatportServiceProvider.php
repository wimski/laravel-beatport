<?php

namespace Wimski\Beatport\Providers;

use Illuminate\Support\ServiceProvider;
use Wimski\Beatport\Contracts\ResourceProcessorFactoryInterface;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Factories\ResourceResourceProcessorFactory;
use Wimski\Beatport\Requests\Request;

class BeatportServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        $this->registerInterfaceBindings();
    }

    protected function registerInterfaceBindings(): self
    {
        $this->app->bind(RequestInterface::class, Request::class);
        $this->app->singleton(ResourceProcessorFactoryInterface::class, ResourceResourceProcessorFactory::class);

        return $this;
    }
}
