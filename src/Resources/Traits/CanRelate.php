<?php

namespace Wimski\Beatport\Resources\Traits;

use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Exceptions\InvalidRelationshipException;
use Wimski\Beatport\Exceptions\ResourceInterfaceException;
use Wimski\Beatport\Requests\Builders\RelationshipRequestBuilder;

trait CanRelate
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
    public static function relationship(string $slug, int $id, string $relationship): RelationshipRequestBuilder
    {
        static::checkIfResourceInterface();

        /** @var ResourceInterface $resource */
        $resource = new static();

        if (! $resource->hasRelationship($relationship)) {
            throw new InvalidRelationshipException("Relationship {$relationship} is not supported " . get_class($resource));
        }

        $builder = resolve(RelationshipRequestBuilder::class, [
            'resource' => new $relationship(),
        ]);

        return $builder
            ->slug($slug)
            ->id($id)
            ->parent($resource);
    }

    public static function relationshipByData(DataInterface $data, string $relationship): RelationshipRequestBuilder
    {
        return static::relationship($data->getSlug(), $data->getId(), $relationship);
    }
}
