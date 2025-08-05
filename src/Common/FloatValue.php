<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Common;

use Doctrine\DBAL\ParameterType;
use Stringable;

abstract readonly class FloatValue implements PersistableValueObject, Stringable
{
    public function __construct(protected float $value) {}

    public static function fromInteger(int $value): static
    {
        return new static(floatval($value));
    }

    public function toFloat(): float
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    #[\Override]
    public function __toString(): string
    {
        return (string) $this->value;
    }

    #[\Override]
    public function jsonSerialize(): float
    {
        return $this->value;
    }

    #[\Override]
    public function rawValue(): float
    {
        return $this->value;
    }

    #[\Override]
    public static function fromRawValue(mixed $value): static
    {
        return static::fromInteger($value);
    }

    #[\Override]
    public function rawType(): ParameterType
    {
        return ParameterType::STRING;
    }
}
