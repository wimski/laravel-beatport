<?php

namespace Wimski\Beatport\Processors\Resources;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Data\Artist;
use Wimski\Beatport\Data\Label;
use Wimski\Beatport\Data\Release;
use Wimski\Beatport\Processors\Crawler;
use Wimski\Beatport\Processors\ResourceUrlProcessor;

class ReleaseResourceProcessor extends AbstractResourceProcessor
{
    /**
     * @var TrackResourceProcessor
     */
    protected $trackResourceProcessor;

    public function __construct(ResourceUrlProcessor $urlProcessor, TrackResourceProcessor $trackResourceProcessor)
    {
        parent::__construct($urlProcessor);

        $this->trackResourceProcessor = $trackResourceProcessor;
    }

    protected function processView(Crawler $html): ?DataInterface
    {
        $url = $html->filter('head meta')->getAttr('[name="og:url"]', 'content');

        $release = new Release($this->urlProcessor->processResourceAttributes($url));

        $left  = $html->get('.interior-release-chart-artwork-parent');
        $right = $html->get('.interior-release-chart-content');

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
                    $release->setLabel(new Label(
                        $this->processAnchor($item->get('.value a')),
                    ));
                    break;

                case 'Catalog':
                    $release->setCatalog($item->getText('.value'));
                    break;

                default:
                    // omitted on purpose
            }
        });

        $artists = $right->filter('.interior-release-chart-content-item [data-artist]')->each(function (Crawler $anchor) {
            return new Artist($this->processAnchor($anchor));
        });
        $release->setArtists(collect($artists));

        $tracks = $right->filter('.bucket.tracks .bucket-item')->each(function (Crawler $item) use ($release) {
            $track = $this->trackResourceProcessor->processRow($item);

            $track
                ->setReleased($release->getReleased())
                ->setRelease($release)
                ->setLabel($release->getLabel());

            return $track;
        });
        $release->setTracks(collect($tracks));

        return $release;
    }

    protected function processIndex(Crawler $html): ?Collection
    {
        return $this->processMultiple($html);
    }

    protected function processRelationship(Crawler $html): ?Collection
    {
        return $this->processMultiple($html);
    }

    protected function processMultiple(Crawler $html): ?Collection
    {
        $items = $html->filter('.bucket-items .bucket-item');

        if (! $items) {
            return null;
        }

        $releases = $items->each(function (Crawler $item) {
            $anchor = $item->get('.buk-horz-release-title a');
            $props  = $this->urlProcessor->processResourceAttributes($anchor->attr('href'));
            $props['title'] = $anchor->text();

            $release = new Release($props);

            $release
                ->setArtwork($item->getAttr('.horz-release-artwork', 'src'))
                ->setReleased($item->getText('.buk-horz-release-released'));

            $labelAnchor = $item->get('.buk-horz-release-labels a');
            $labelProps  = $this->urlProcessor->processResourceAttributes($labelAnchor->attr('href'));
            $labelProps['title'] = $labelAnchor->text();
            $release->setLabel(new Label($labelProps));

            $artists = $item->filter('.buk-horz-release-artists a')->each(function (Crawler $anchor) {
                $props = $this->urlProcessor->processResourceAttributes($anchor->attr('href'));
                $props['title'] = $anchor->text();

                return new Artist($props);
            });
            $release->setArtists(collect($artists));

            return $release;
        });

        return collect($releases);
    }

    protected function processSearch(Crawler $html): ?Collection
    {
        $items = $html->filter('.bucket-items .bucket-item');

        if (! $items) {
            return null;
        }

        $releases = $items->each(function (Crawler $item) {
            $release = new Release(
                $this->processAnchor($item->get('.release-title a:last-child')),
            );

            $release
                ->setArtwork($item->getAttr('.release-artwork', 'src'))
                ->setLabel(new Label(
                    $this->processAnchor($item->get('.release-label a'))
                ));

            $artists = $item->filter('.release-artists a')->each(function (Crawler $anchor) {
                return new Artist($this->processAnchor($anchor));
            });
            $release->setArtists(collect($artists));

            return $release;
        });

        return collect($releases);
    }
}
