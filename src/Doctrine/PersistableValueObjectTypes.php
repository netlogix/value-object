<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Doctrine;

use Doctrine\DBAL\Types\TypeRegistry;
use Netlogix\ValueObject\Doctrine\Types\PersistableValueObjectType;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class PersistableValueObjectTypes
{
    public function __construct(private array $types)
    {
    }

    public function register(TypeRegistry $types): void
    {
        foreach ($this->types as $doctrineTypeName => $valueObjectFQCN) {
            if (!$types->has($doctrineTypeName)) {
                $types->register(
                    $doctrineTypeName,
                    PersistableValueObjectType::create($doctrineTypeName, $valueObjectFQCN)
                );
            }
        }
    }
}
