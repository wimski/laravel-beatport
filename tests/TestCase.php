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

    protected function getStubsPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            'stubs',
        ]);
    }

    protected function loadHtmlStub(string $fileName): string
    {
        return file_get_contents(implode(DIRECTORY_SEPARATOR, [
            $this->getStubsPath(),
            'html',
            "{$fileName}.html",
        ]));
    }

    protected function getResponsesPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->getStubsPath(),
            'responses',
        ]);
    }
}
