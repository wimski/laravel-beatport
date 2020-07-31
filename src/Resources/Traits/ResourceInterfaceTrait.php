<?php

namespace Wimski\Beatport\Resources\Traits;

use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Exceptions\ResourceInterfaceException;

trait ResourceInterfaceTrait
{
    /**
     * @throws ResourceInterfaceException
     */
    protected static function checkIfResourceInterface(): void
    {
        if (! static::isResourceInterface()) {
            throw new ResourceInterfaceException('The calling class must implement ResourceInterface');
        }
    }

    protected static function isResourceInterface(): bool
    {
        return is_subclass_of(get_called_class(), ResourceInterface::class);
    }
}
