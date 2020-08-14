<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Data\SubGenre;
use Wimski\Beatport\Processors\Crawler;

class SubGenreResourceProcessor extends AbstractGenreResourceProcessor
{
    protected function processView(Crawler $html): ?DataInterface
    {
        /** @var SubGenre $subGenre */
        $subGenre = $this->getGenreFromItem(
            SubGenre::class,
            $html->get('.filter-sub-genre-drop li'),
        );

        /** @var Genre $genre */
        $genre = $this->getGenreFromItem(
            Genre::class,
            $html->get('.filter-genre-drop li'),
        );
        $subGenre->setGenre($genre);

        return $subGenre;
    }

    protected function processIndex(Crawler $html): ?Collection
    {
        $subGenres = $html->filter('.filter-sub-genre-drop li')->each(function (Crawler $item) {
            return $this->getGenreFromItem(SubGenre::class, $item);
        });

        return collect($subGenres);
    }
}
