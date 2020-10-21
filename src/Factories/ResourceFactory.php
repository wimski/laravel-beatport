<?php

namespace Wimski\Beatport\Factories;

use Wimski\Beatport\Contracts\ResourceFactoryInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Exceptions\InvalidResourceException;
use Wimski\Beatport\Resources\ArtistResource;
use Wimski\Beatport\Resources\GenreResource;
use Wimski\Beatport\Resources\KeyResource;
use Wimski\Beatport\Resources\LabelResource;
use Wimski\Beatport\Resources\ReleaseResource;
use Wimski\Beatport\Resources\SubGenreResource;
use Wimski\Beatport\Resources\TrackResource;

class ResourceFactory implements ResourceFactoryInterface
{
    /**
     * @var string[]
     */
    protected $map = [
        ResourceTypeEnum::ARTIST    => ArtistResource::class,
        ResourceTypeEnum::GENRE     => GenreResource::class,
        ResourceTypeEnum::KEY       => KeyResource::class,
        ResourceTypeEnum::LABEL     => LabelResource::class,
        ResourceTypeEnum::RELEASE   => ReleaseResource::class,
        ResourceTypeEnum::SUB_GENRE => SubGenreResource::class,
        ResourceTypeEnum::TRACK     => TrackResource::class,
    ];

    public function make($value): ResourceInterface
    {
        if (! ResourceTypeEnum::isValid($value)) {
            throw new InvalidResourceException("Cannot make a resource for type {$value}");
        }

        $value = (new ResourceTypeEnum($value))->getValue();

        $className = $this->map[$value];

        return new $className();
    }
}
