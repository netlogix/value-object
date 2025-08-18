<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Common;

use Doctrine\DBAL\ParameterType;
use Stringable;
use Symfony\Component\Uid\Uuid;

abstract readonly class Identifier implements PersistableValueObject, Stringable
{
    public function __construct(protected string $identifier) {}

    public static function createNew(): static
    {
        return static::fromString(Uuid::v4()->toRfc4122());
    }

    public static function createEmpty(): static
    {
        return static::fromString('');
    }

    public static function fromUuid(Uuid $uuid): static
    {
        return static::fromString($uuid->toString());
    }

    public static function fromString(string $identifier): static
    {
        return new static($identifier);
    }

    public function toString(): string
    {
        return $this->identifier;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->identifier;
    }

    #[\Override]
    public function jsonSerialize(): string
    {
        return $this->identifier;
    }

    public function equals(self $other): bool
    {
        return $this->identifier === $other->identifier;
    }

    #[\Override]
    public function rawValue(): string
    {
        return $this->identifier;
    }

    #[\Override]
    public static function fromRawValue(mixed $value): static
    {
        return static::fromString($value);
    }

    #[\Override]
    public function rawType(): ParameterType
    {
        return ParameterType::STRING;
    }

    public static function fromValueObjects(PersistableValueObject ...$valueObjects): static
    {
        return new static(
            Uuid::fromBinary(
                hex2bin(
                    hash(
                        'xxh128',
                        json_encode(
                            strtolower(
                                implode(
                                    '-',
                                    array_map(
                                        static fn(
                                            PersistableValueObject $valueObject,
                                        ): mixed => $valueObject->rawValue(),
                                        $valueObjects,
                                    ),
                                ),
                            ),
                            JSON_THROW_ON_ERROR,
                        ),
                    ),
                ),
            )->toString(),
        );
    }
}
