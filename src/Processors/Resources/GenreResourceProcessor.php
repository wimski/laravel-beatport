<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Data\SubGenre;
use Wimski\Beatport\Processors\Crawler;

class GenreResourceProcessor extends AbstractGenreResourceProcessor
{
    protected function processView(Crawler $html): ?DataInterface
    {
        /** @var Genre $genre */
        $genre = $this->getGenreFromItem(
            Genre::class,
            $html->get('.filter-genre-drop li'),
        );

        $subGenres = $html->filter('.filter-sub-genre-drop li')->each(function (Crawler $item) {
            return $this->getGenreFromItem(SubGenre::class, $item);
        });
        $genre->setSubGenres(collect($subGenres));

        return $genre;
    }

    protected function processIndex(Crawler $html): ?Collection
    {
        $genres = $html->filter('.genre-drop-list__genre')->each(function (Crawler $item) {
            return new Genre($this->processAnchor($item));
        });

        return collect($genres);
    }
}
