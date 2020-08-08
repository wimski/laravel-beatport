<?php

namespace Wimski\Beatport\Tests\Data;

use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Tests\TestCase;

abstract class AbstractDataTest extends TestCase
{
    abstract protected function dataClass(): string;

    protected function getDataObject(array $properties = null): DataInterface
    {
        $class = $this->dataClass();

        return new $class($properties);
    }

    /**
     * @test
     */
    public function it_has_a_title(): void
    {
        $object = $this->getDataObject(['title' => 'title']);
        static::assertEquals('title', $object->getTitle());

        $object = $this->getDataObject();
        static::assertNull($object->getTitle());

        $object->setTitle('title');
        static::assertEquals('title', $object->getTitle());
    }

    /**
     * @test
     */
    public function it_has_a_slug(): void
    {
        $object = $this->getDataObject(['slug' => 'slug']);
        static::assertEquals('slug', $object->getSlug());

        $object = $this->getDataObject();
        static::assertNull($object->getSlug());

        $object->setSlug('slug');
        static::assertEquals('slug', $object->getSlug());
    }

    /**
     * @test
     */
    public function it_has_an_id(): void
    {
        $object = $this->getDataObject(['id' => 1]);
        static::assertEquals(1, $object->getId());

        $object = $this->getDataObject();
        static::assertNull($object->getId());

        $object->setId(1);
        static::assertEquals(1, $object->getId());
    }
}
