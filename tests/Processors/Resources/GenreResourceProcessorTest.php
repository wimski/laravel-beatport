<?php

namespace Wimski\Beatport\Tests\Processors\Resources;

use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Processors\Resources\GenreResourceProcessor;
use Wimski\Beatport\Tests\TestCase;

class GenreResourceProcessorTest extends TestCase
{
    /**
     * @var GenreResourceProcessor
     */
    protected $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->app->make(GenreResourceProcessor::class);
    }

    /**
     * @test
     */
    public function it_processes_view_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::VIEW(),
            $this->loadHtmlStub('genre.view'),
        );

        static::assertInstanceOf(Genre::class, $data);

        /** @var Genre $data */
        static::assertSame(2, $data->getId());
        static::assertSame('house', $data->getSlug());
        static::assertSame('House', $data->getTitle());
        static::assertCount(2, $data->getSubGenres());

        $subGenre1 = $data->getSubGenres()->get(0);
        static::assertSame(5, $subGenre1->getId());
        static::assertSame('deep-house', $subGenre1->getSlug());
        static::assertSame('Deep House', $subGenre1->getTitle());

        $subGenre2 = $data->getSubGenres()->get(1);
        static::assertSame(8, $subGenre2->getId());
        static::assertSame('tech-house', $subGenre2->getSlug());
        static::assertSame('Tech House', $subGenre2->getTitle());
    }

    /**
     * @test
     */
    public function it_processes_index_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::INDEX(),
            $this->loadHtmlStub('genre.index'),
        );

        static::assertCount(2, $data);

        $genre1 = $data->get(0);
        static::assertSame(39, $genre1->getId());
        static::assertSame('dance', $genre1->getSlug());
        static::assertSame('Dance', $genre1->getTitle());

        $genre2 = $data->get(1);
        static::assertSame(12, $genre2->getId());
        static::assertSame('deep-house', $genre2->getSlug());
        static::assertSame('Deep House', $genre2->getTitle());
    }
}
