<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Common;

use Doctrine\DBAL\ParameterType;
use Stringable;

abstract readonly class BooleanValue implements PersistableValueObject, Stringable
{
    public function __construct(protected bool $value) {}

    public static function fromBoolean(bool $value): static
    {
        return new static($value);
    }

    public function toBoolean(): bool
    {
        return $this->value;
    }

    #[\Override]
    public function __toString(): string
    {
        return (string) $this->value;
    }

    #[\Override]
    public function jsonSerialize(): bool
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    #[\Override]
    public function rawValue(): bool
    {
        return $this->value;
    }

    #[\Override]
    public static function fromRawValue(mixed $value): static
    {
        return static::fromBoolean($value);
    }

    #[\Override]
    public function rawType(): ParameterType
    {
        return ParameterType::BOOLEAN;
    }
}
