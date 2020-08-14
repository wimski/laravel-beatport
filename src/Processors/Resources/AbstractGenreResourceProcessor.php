<?php

namespace Wimski\Beatport\Processors\Resources;

use Cocur\Slugify\SlugifyInterface;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Processors\Crawler;
use Wimski\Beatport\Processors\ResourceUrlProcessor;

abstract class AbstractGenreResourceProcessor extends AbstractResourceProcessor
{
    /**
     * @var SlugifyInterface
     */
    protected $slugify;

    public function __construct(ResourceUrlProcessor $urlProcessor, SlugifyInterface $slugify)
    {
        parent::__construct($urlProcessor);

        $this->slugify = $slugify;
    }

    protected function getGenreFromItem(string $class, Crawler $item): DataInterface
    {
        $genre = new $class();

        $title = $item->getText('label');

        $genre
            ->setId((int) $item->getAttr('input', 'name'))
            ->setSlug($this->slugify->slugify($title))
            ->setTitle($title);

        return $genre;
    }
}
