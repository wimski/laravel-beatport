<?php

namespace Wimski\Beatport\Tests\Processors\Resources;

use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Processors\Resources\LabelResourceProcessor;
use Wimski\Beatport\Tests\TestCase;

class LabelResourceProcessorTest extends TestCase
{
    /**
     * @var LabelResourceProcessor
     */
    protected $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->app->make(LabelResourceProcessor::class);
    }

    /**
     * @test
     */
    public function it_processes_view_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::VIEW(),
            $this->loadHtmlStub('label.view'),
        );

        static::assertInstanceOf(Label::class, $data);

        /** @var Label $data */
        static::assertSame(88162, $data->getId());
        static::assertSame('xploded-music-limited', $data->getSlug());
        static::assertSame('Xploded Music Limited', $data->getTitle());
        static::assertSame(
            'https://geo-media.beatport.com/image/cda9862c-cf92-4d13-ac65-7e9277181f51.jpg',
            $data->getArtwork(),
        );
    }

    /**
     * @test
     */
    public function it_processes_search_data(): void
    {
        $data = $this->processor->process(
            RequestTypeEnum::QUERY(),
            $this->loadHtmlStub('label.search'),
        );

        static::assertCount(2, $data);

        $label1 = $data->get(0);
        static::assertSame(56833, $label1->getId());
        static::assertSame('vii', $label1->getSlug());
        static::assertSame('VII', $label1->getTitle());
        static::assertSame(
            'https://geo-media.beatport.com/image/898ace7c-859a-4593-9852-007d56ae6678.jpg',
            $label1->getArtwork(),
        );

        $label2 = $data->get(1);
        static::assertSame(42069, $label2->getId());
        static::assertSame('vii-angels-records', $label2->getSlug());
        static::assertSame('VII Angels Records', $label2->getTitle());
        static::assertSame(
            'https://geo-media.beatport.com/image/fc0732da-95e2-4b12-bf2d-8d329b7f8b74.jpg',
            $label2->getArtwork(),
        );
    }
}
