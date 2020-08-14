<?php

namespace Wimski\Beatport\Tests\Resources\Traits;

use Wimski\Beatport\Exceptions\InvalidRelationshipException;
use Wimski\Beatport\Tests\Stubs\Classes\UseCanRelateTrait;
use Wimski\Beatport\Tests\TestCase;

class CanRelateTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_for_unsupported_relationship(): void
    {
        static::expectException(InvalidRelationshipException::class);
        static::expectExceptionMessage("Relationship foo is not supported ");

        UseCanRelateTrait::relationship('', 1, 'foo');
    }
}
