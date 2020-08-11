<?php

namespace Wimski\Beatport\Tests\Processors;

use Wimski\Beatport\Processors\PaginationProcessor;
use Wimski\Beatport\Requests\Pagination;
use Wimski\Beatport\Tests\TestCase;

class PaginationProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function it_processes_pagination(): void
    {
        $processor = new PaginationProcessor();

        $pagination = $processor->process('
            <div class="pagination-container">
                <div class="pag-number-current">1</div>
                <div class="pag-number">2</div>
                <div class="pag-number">3</div>
                <div class="pag-number">4</div>
                <div class="pag-number">5</div>
            </div>
        ');

        static::assertInstanceOf(Pagination::class, $pagination);
        static::assertSame(1, $pagination->current());
        static::assertSame(5, $pagination->total());

        $pagination = $processor->process('');

        static::assertNull($pagination);
    }
}
