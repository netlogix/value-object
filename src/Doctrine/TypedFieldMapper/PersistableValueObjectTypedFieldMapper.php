<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Doctrine\TypedFieldMapper;

use Doctrine\ORM\Mapping\TypedFieldMapper;
use Netlogix\ValueObject\Common\PersistableValueObject;
use ReflectionNamedType;
use ReflectionProperty;

final readonly class PersistableValueObjectTypedFieldMapper implements TypedFieldMapper
{
    public function validateAndComplete(array $mapping, ReflectionProperty $field): array
    {
        $type = $field->getType();

        if (
            !isset($mapping['type']) &&
            $type instanceof ReflectionNamedType &&
            (!$type->isBuiltin() && is_subclass_of($type->getName(), PersistableValueObject::class))
        ) {
            $mapping['type'] = $type->getName();
        }

        return $mapping;
    }
}
