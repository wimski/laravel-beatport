<?php

namespace Wimski\Beatport\Processors;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Artist;
use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Data\SubGenre;
use Wimski\Beatport\Data\Track;

class TrackProcessor extends AbstractProcessor
{
    protected function processSingle(Crawler $meta, Crawler $root): ?DataInterface
    {
        $interior = $root->get('.interior');
        if (! $interior) {
            return null;
        }

        $type = $interior->getText('.interior-type');
        if ($type !== 'Track') {
            return null;
        }

        $url = $meta->getAttr('[name="og:url"]', 'content');

        $track = new Track($this->parseUrl($url));

        $track
            ->setTitle($interior->getText('.interior-title h1'))
            ->setLength($interior->getText('.interior-track-length .value'))
            ->setReleased($interior->getText('.interior-track-released .value'))
            ->setBpm($interior->getText('.interior-track-bpm .value'))
            ->setKey($interior->getText('.interior-track-key .value'));

        $remixed = $interior->get('.interior-title .remixed');
        if (! $remixed) {
            $track->setRemix($remixed->text());
        }

        $genreAnchor         = $interior->get('.interior-track-genre .value a');
        $genreProps          = $this->parseUrl($genreAnchor->attr('href'));
        $genreProps['title'] = $genreAnchor->text();
        $track->setGenre(new Genre($genreProps));

        $subGenreAnchor = $interior->get('.interior-track-genre .value.sep a');
        if ($subGenreAnchor) {
            $subGenreProps          = $this->parseUrl($subGenreAnchor->attr('href'));
            $subGenreProps['title'] = $subGenreAnchor->text();
            $track->setSubGenre(new SubGenre($subGenreProps));
        }

        $labelAnchor         = $interior->get('.interior-track-labels .value a');
        $labelProps          = $this->parseUrl($labelAnchor->attr('href'));
        $labelProps['title'] = $labelAnchor->text();
        $track->setLabel(new Label($labelProps));

        $release                 = $interior->get('.interior-track-releases-artwork-container');
        $releaseAnchor           = $release->get('a');
        $releaseProps            = $this->parseUrl($releaseAnchor->attr('href'));
        $releaseProps['artwork'] = $releaseAnchor->getAttr('img', 'src');
        $releaseProps['title']   = $release->attr('data-ec-name');
        $track->setRelease(new Release($releaseProps));

        $interior->filter('.interior-track-artists')->each(function (Crawler $artists) use ($track) {
            $category = $artists->getText('.category');

            $artists->filter('a')->each(function (Crawler $artistAnchor) use ($track, $category) {
                $props = $this->parseUrl($artistAnchor->attr('href'));
                $props['title'] = $artistAnchor->text();

                $artist = new Artist($props);

                if ($category === 'Artists') {
                    $track->addArtist($artist);
                } elseif ($category === 'Remixers') {
                    $track->addRemixer($artist);
                }
            });
        });

        return $track;
    }

    protected function processMultiple(Crawler $meta, Crawler $root): ?Collection
    {
        $items = $root->filter('.bucket-items .bucket-item');

        if (! $items) {
            return null;
        }

        $tracks = $items->each(function (Crawler $item) {
            $anchor = $item->get('.buk-track-title a');
            $props  = $this->parseUrl($anchor->attr('href'));
            $props['title'] = $anchor->getText('.buk-track-primary-title');

            $track = new Track($props);

            $track
                ->setKey($item->getText('.buk-track-key'))
                ->setReleased($item->getText('.buk-track-released'));

            $remix = $anchor->get('.buk-track-remixed');
            if ($remix) {
                $track->setRemix($remix->text());
            }

            $releaseAnchor = $item->get('.buk-track-artwork-parent');
            $releaseProps  = $this->parseUrl($releaseAnchor->getAttr('a', 'href'));
            $track->setRelease(new Release($releaseProps));
            $track->getRelease()->setArtwork($releaseAnchor->getAttr('img', 'src'));

            $labelAnchor = $item->get('.buk-track-labels a');
            $labelProps  = $this->parseUrl($labelAnchor->attr('href'));
            $labelProps['title'] = $labelAnchor->text();
            $track->setLabel(new Label($labelProps));

            $artists = $item->filter('.buk-track-artists a')->each(function (Crawler $anchor) {
                $props = $this->parseUrl($anchor->attr('href'));
                $props['title'] = $anchor->text();

                return new Artist($props);
            });

            if (! empty($artists)) {
                $track->setArtists(collect($artists));
            }

            $remixers = $item->filter('.buk-track-remixers a')->each(function (Crawler $anchor) {
                $props = $this->parseUrl($anchor->attr('href'));
                $props['title'] = $anchor->text();

                return new Artist($props);
            });

            if (! empty($remixers)) {
                $track->setRemixers(collect($remixers));
            }

            return $track;
        });

        return collect($tracks);
    }
}
