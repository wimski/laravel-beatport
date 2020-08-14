<?php

namespace Wimski\Beatport\Tests\Resources\Traits;

use Wimski\Beatport\Exceptions\ResourceInterfaceException;
use Wimski\Beatport\Tests\Stubs\Classes\UseResourceInterfaceTrait;
use Wimski\Beatport\Tests\TestCase;

class ResourceInterfaceTraitTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_when_class_is_not_resource(): void
    {
        static::expectException(ResourceInterfaceException::class);
        static::expectExceptionMessage('The calling class must implement ResourceInterface');

        UseResourceInterfaceTrait::test();
    }
}
