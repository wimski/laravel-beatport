<?php

namespace Wimski\Beatport\Processors;

use Wimski\Beatport\Requests\Pagination;

class PaginationProcessor
{
    public function process(string $html): ?Pagination
    {
        $crawler = new Crawler($html);

        $pagination = $crawler->get('.pagination-container');

        if (! $pagination) {
            return null;
        }

        return new Pagination(
            (int) $pagination->getText('.pag-number-current'),
            (int) $pagination->filter('.pag-number')->last()->text(),
        );
    }
}
