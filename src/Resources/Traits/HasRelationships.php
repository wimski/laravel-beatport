<?php

namespace Wimski\Beatport\Resources\Traits;

use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Exceptions\InvalidRelationshipException;
use Wimski\Beatport\Exceptions\ResourceInterfaceException;
use Wimski\Beatport\Requests\Builders\RelationshipRequestBuilder;

trait HasRelationships
{
    use ResourceInterfaceTrait;

    /**
     * @param string $slug
     * @param int $id
     * @param string $relationship
     * @return RelationshipRequestBuilder
     * @throws InvalidRelationshipException
     * @throws ResourceInterfaceException
     */
    public static function relationship(string $slug, int $id, string $relationship)
    {
        static::checkIfResourceInterface();

        /** @var ResourceInterface $resource */
        $resource = new static();

        if (! $resource->hasRelationship($relationship)) {
            throw new InvalidRelationshipException("Relationship {$relationship} is not supported " . get_class($resource));
        }

        return (new RelationshipRequestBuilder(new $relationship()))
            ->slug($slug)
            ->id($id)
            ->parent($resource);
    }
}
