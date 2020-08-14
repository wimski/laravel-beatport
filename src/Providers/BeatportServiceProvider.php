<?php

namespace Wimski\Beatport\Providers;

use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use Wimski\Beatport\Contracts\ResourceProcessorFactoryInterface;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Factories\ResourceProcessorFactory;
use Wimski\Beatport\Requests\Request;

class BeatportServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        $this
            ->registerConfig()
            ->registerInterfaceBindings();
    }

    protected function registerConfig(): self
    {
        $this->mergeConfigFrom(
            realpath(dirname(__DIR__) . '/../config/beatport.php'),
            'beatport',
        );

        return $this;
    }

    protected function registerInterfaceBindings(): self
    {
        $this->app->bind(RequestInterface::class, Request::class);
        $this->app->singleton(ResourceProcessorFactoryInterface::class, ResourceProcessorFactory::class);
        $this->app->singleton(ClientInterface::class, Client::class);
        $this->app->bind(SlugifyInterface::class, Slugify::class);

        return $this;
    }
}
