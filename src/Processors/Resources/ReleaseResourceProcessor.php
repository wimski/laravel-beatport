<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Artist;
use Wimski\Beatport\Data\Genre;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Data\Track;
use Wimski\Beatport\Processors\Crawler;

class ReleaseResourceProcessor extends AbstractResourceProcessor
{
    protected function processSingle(): ?DataInterface
    {
        $interior = $this->getContentRoot()->get('.interior');
        if (! $interior) {
            return null;
        }

        $type = $interior->getText('.interior-release-chart-content h3');
        if ($type !== 'Release') {
            return null;
        }

        $url = $this->html->filter('head meta')->getAttr('[name="og:url"]', 'content');

        $release = new Release($this->urlProcessor->process($url));

        $left  = $interior->get('.interior-release-chart-artwork-parent');
        $right = $interior->get('.interior-release-chart-content');

        $release
            ->setTitle($right->getText('h1'))
            ->setArtwork($left->getAttr('.interior-release-chart-artwork', 'src'))
            ->setDescription($left->getText('.interior-expandable'));

        $left->filter('.interior-release-chart-content-item')->each(function (Crawler $item) use ($release) {
            $category = $item->getText('.category');

            switch ($category) {
                case 'Release Date':
                    $release->setReleased($item->getText('.value'));
                    break;

                case 'Label':
                    $labelAnchor = $item->get('.value a');
                    $labelProps  = $this->urlProcessor->process($labelAnchor->attr('href'));
                    $labelProps['title'] = $labelAnchor->text();
                    $release->setLabel(new Label($labelProps));
                    break;

                case 'Catalog':
                    $release->setCatalog($item->getText('.value'));
                    break;

                default:
                    // omitted on purpose
            }
        });

        $artists = $right->filter('.interior-release-chart-content-item [data-artist]')->each(function (Crawler $anchor) {
            $props = $this->urlProcessor->process($anchor->attr('href'));
            $props['title'] = $anchor->text();
            return new Artist($props);
        });
        $release->setArtists(collect($artists));

        $tracks = $right->filter('.bucket.tracks .bucket-item')->each(function (Crawler $item) use ($release) {
            $anchor = $item->get('.buk-track-title a');
            $props  = $this->urlProcessor->process($anchor->attr('href'));
            $props['title'] = $anchor->getText('.buk-track-primary-title');

            $track = new Track($props);

            $remix = $anchor->get('.buk-track-remixed');
            if ($remix) {
                $track->setRemix($remix->text());
            }

            $track
                ->setKey($item->getText('.buk-track-key'))
                ->setBpm($item->getText('.buk-track-bpm'))
                ->setLength($item->getText('.buk-track-length'))
                ->setReleased($release->getReleased())
                ->setRelease($release)
                ->setLabel($release->getLabel());

            $genreAnchor = $item->get('.buk-track-genre a');
            $genreProps  = $this->urlProcessor->process($genreAnchor->attr('href'));
            $genreProps['title'] = $genreAnchor->text();
            $track->setGenre(new Genre($genreProps));

            $artists = $item->filter('.buk-track-artists a')->each(function (Crawler $anchor) {
                $props = $this->urlProcessor->process($anchor->attr('href'));
                $props['title'] = $anchor->text();

                return new Artist($props);
            });

            if (! empty($artists)) {
                $track->setArtists(collect($artists));
            }

            $remixers = $item->filter('.buk-track-remixers a')->each(function (Crawler $anchor) {
                $props = $this->urlProcessor->process($anchor->attr('href'));
                $props['title'] = $anchor->text();

                return new Artist($props);
            });

            if (! empty($remixers)) {
                $track->setRemixers(collect($remixers));
            }

            return $track;
        });
        $release->setTracks(collect($tracks));

        return $release;
    }

    protected function processMultiple(): ?Collection
    {
        $items = $this->getContentRoot()->filter('.bucket-items .bucket-item');

        if (! $items) {
            return null;
        }

        $releases = $items->each(function (Crawler $item) {
            $anchor = $item->get('.buk-horz-release-title a');
            $props  = $this->urlProcessor->process($anchor->attr('href'));
            $props['title'] = $anchor->text();

            $release = new Release($props);

            $release
                ->setArtwork($item->getAttr('.horz-release-artwork', 'src'))
                ->setReleased($item->getText('.buk-horz-release-released'));

            $labelAnchor = $item->get('.buk-horz-release-labels a');
            $labelProps  = $this->urlProcessor->process($labelAnchor->attr('href'));
            $labelProps['title'] = $labelAnchor->text();
            $release->setLabel(new Label($labelProps));

            $artists = $item->filter('.buk-horz-release-artists a')->each(function (Crawler $anchor) {
                $props = $this->urlProcessor->process($anchor->attr('href'));
                $props['title'] = $anchor->text();

                return new Artist($props);
            });
            $release->setArtists(collect($artists));

            return $release;
        });

        return collect($releases);
    }
}
