<?php

namespace Wimski\Beatport\Tests\Processors\Resources;

use Wimski\Beatport\Data\SubGenre;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Processors\Resources\SubGenreResourceProcessor;
use Wimski\Beatport\Tests\TestCase;

class SubGenreResourceProcessorTest extends TestCase
{
    /**
     * @var SubGenreResourceProcessor
     */
    protected $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->app->make(SubGenreResourceProcessor::class);
    }

    /**
     * @test
     */
    public function it_processes_view_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::VIEW(),
            $this->loadHtmlStub('sub-genre.view'),
        );

        static::assertInstanceOf(SubGenre::class, $data);

        /** @var SubGenre $data */
        static::assertSame(5, $data->getId());
        static::assertSame('deep-house', $data->getSlug());
        static::assertSame('Deep House', $data->getTitle());

        $genre = $data->getGenre();
        static::assertSame(2, $genre->getId());
        static::assertSame('house', $genre->getSlug());
        static::assertSame('House', $genre->getTitle());
    }

    /**
     * @test
     */
    public function it_processes_index_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::INDEX(),
            $this->loadHtmlStub('sub-genre.index'),
        );

        static::assertCount(2, $data);

        $subGenre1 = $data->get(0);
        static::assertSame(5, $subGenre1->getId());
        static::assertSame('deep-house', $subGenre1->getSlug());
        static::assertSame('Deep House', $subGenre1->getTitle());

        $subGenre2 = $data->get(1);
        static::assertSame(8, $subGenre2->getId());
        static::assertSame('tech-house', $subGenre2->getSlug());
        static::assertSame('Tech House', $subGenre2->getTitle());
    }
}
