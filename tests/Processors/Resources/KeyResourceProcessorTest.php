<?php

namespace Wimski\Beatport\Tests\Processors\Resources;

use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Processors\Resources\KeyResourceProcessor;
use Wimski\Beatport\Tests\TestCase;

class KeyResourceProcessorTest extends TestCase
{
    /**
     * @var KeyResourceProcessor
     */
    protected $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->app->make(KeyResourceProcessor::class);
    }

    /**
     * @test
     */
    public function it_processes_index_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::INDEX(),
            $this->loadHtmlStub('key.index'),
        );

        static::assertCount(2, $data);

        $key1 = $data->get(0);
        static::assertSame(5, $key1->getId());
        static::assertSame('A minor', $key1->getTitle());

        $key2 = $data->get(1);
        static::assertSame(8, $key2->getId());
        static::assertSame('D major', $key2->getTitle());
    }
}
