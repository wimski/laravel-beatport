<?php

namespace Wimski\Beatport\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Wimski\Beatport\Providers\BeatportServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            BeatportServiceProvider::class,
        ];
    }
}
