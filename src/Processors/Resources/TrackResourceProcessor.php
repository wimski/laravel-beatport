<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Artist;
use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Data\SubGenre;
use Wimski\Beatport\Data\Track;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Processors\Crawler;

class TrackResourceProcessor extends AbstractResourceProcessor
{
    protected function processView(Crawler $html): ?DataInterface
    {
        $url = $html->filter('head meta')->getAttr('[name="og:url"]', 'content');

        $track = new Track($this->urlProcessor->processResourceAttributes($url));

        $track
            ->setTitle($html->getText('.interior-title h1'))
            ->setLength($html->getText('.interior-track-length .value'))
            ->setReleased($html->getText('.interior-track-released .value'))
            ->setBpm($html->getText('.interior-track-bpm .value'))
            ->setKey($html->getText('.interior-track-key .value'))
            ->setWaveform($html->getAttr('.interior-track-waveform', 'data-src'))
            ->setLabel(new Label(
                $this->processAnchor($html->get('.interior-track-labels .value a')),
            ))
            ->setGenre(new Genre(
                $this->processAnchor($html->get('.interior-track-genre .value a')),
            ));

        $remixed = $html->get('.interior-title .remixed');
        if ($remixed->count()) {
            $track->setRemix($remixed->text());
        }

        $subGenreAnchor = $html->get('.interior-track-genre .value.sep a');
        if ($subGenreAnchor->count()) {
            $track->setSubGenre(new SubGenre(
                $this->processAnchor($subGenreAnchor),
            ));
        }

        $release                 = $html->get('.interior-track-releases-artwork-container');
        $releaseAnchor           = $release->get('a');
        $releaseProps            = $this->urlProcessor->processResourceAttributes($releaseAnchor->attr('href'));
        $releaseProps['artwork'] = $releaseAnchor->getAttr('img', 'src');
        $releaseProps['title']   = $release->attr('data-ec-name');
        $track->setRelease(new Release($releaseProps));

        $html->filter('.interior-track-artists')->each(function (Crawler $artists) use ($track) {
            $category = $artists->getText('.category');

            $artists->filter('a')->each(function (Crawler $anchor) use ($track, $category) {
                $artist = new Artist($this->processAnchor($anchor));

                if ($category === 'Artists') {
                    $track->addArtist($artist);
                } elseif ($category === 'Remixers') {
                    $track->addRemixer($artist);
                }
            });
        });

        return $track;
    }

    protected function processIndex(Crawler $html): ?Collection
    {
        return $this->processMultiple($html);
    }

    protected function processRelationship(Crawler $html): ?Collection
    {
        return $this->processMultiple($html);
    }

    protected function processSearch(Crawler $html): ?Collection
    {
        return $this->processMultiple($html);
    }

    protected function processMultiple(Crawler $html): ?Collection
    {
        $items = $html->filter('.bucket-items .bucket-item');

        if (! $items) {
            return null;
        }

        $tracks = $items->each(function (Crawler $item) {
            $track = $this->processRow($item);

            $track
                ->setReleased($item->getText('.buk-track-released'))
                ->setLabel(new Label(
                    $this->processAnchor($item->get('.buk-track-labels a')),
                ));

            return $track;
        });

        return collect($tracks);
    }

    public function processRow(Crawler $row): Track
    {
        $anchor = $row->get('.buk-track-title a');
        $props  = $this->urlProcessor->processResourceAttributes($anchor->attr('href'));
        $props['title'] = $anchor->getText('.buk-track-primary-title');

        $track = new Track($props);

        $track->setKey($row->getText('.buk-track-key'));

        $remix = $anchor->get('.buk-track-remixed');
        if ($remix) {
            $track->setRemix($remix->text());
        }

        $genreProps = $this->processAnchor($row->get('.buk-track-genre a'));
        if (ResourceTypeEnum::SUB_GENRE()->equals($genreProps['type'])) {
            $track->setSubGenre(new SubGenre($genreProps));
        } else {
            $track->setGenre(new Genre($genreProps));
        }

        $releaseAnchor = $row->get('.buk-track-artwork-parent');
        $releaseProps  = $this->urlProcessor->processResourceAttributes($releaseAnchor->getAttr('a', 'href'));
        $track->setRelease(new Release($releaseProps));
        $track->getRelease()->setArtwork($releaseAnchor->getAttr('img', 'src'));

        $artists = $row->filter('.buk-track-artists a')->each(function (Crawler $anchor) {
            return new Artist($this->processAnchor($anchor));
        });

        if (! empty($artists)) {
            $track->setArtists(collect($artists));
        }

        $remixers = $row->filter('.buk-track-remixers a')->each(function (Crawler $anchor) {
            return new Artist($this->processAnchor($anchor));
        });

        if (! empty($remixers)) {
            $track->setRemixers(collect($remixers));
        }

        return $track;
    }
}
