<?php

namespace Wimski\Beatport\Tests\Processors;

use Wimski\Beatport\Processors\Crawler;
use Wimski\Beatport\Tests\TestCase;

class CrawlerTest extends TestCase
{
    /**
     * @var Crawler
     */
    protected $crawler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->crawler = new Crawler('
            <div class="foo">
                <div class="bar-1">Lorem</div>
                <div class="bar-2">Ipsum</div>
            </div>
        ');
    }

    /**
     * @test
     */
    public function it_gets_the_first_filtered_node(): void
    {
        $node = $this->crawler->get('.foo div');
        static::assertSame('Lorem', $node->text());
        static::assertSame('bar-1', $node->attr('class'));

        $node = $this->crawler->get('.thing');
        static::assertSame(0, $node->count());
    }

    /**
     * @test
     */
    public function it_gets_the_text_of_the_first_filtered_node(): void
    {
        $text = $this->crawler->getText('.foo div');
        static::assertSame('Lorem', $text);

        $text = $this->crawler->getText('.thing');
        static::assertNull($text);
    }

    /**
     * @test
     */
    public function it_get_an_attribute_of_the_first_filtered_node(): void
    {
        $attr = $this->crawler->getAttr('.foo div', 'class');
        static::assertSame('bar-1', $attr);

        $attr = $this->crawler->getAttr('.thing', 'class');
        static::assertNull($attr);
    }
}
