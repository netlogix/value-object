<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Common;

use Doctrine\DBAL\ParameterType;
use Stringable;

abstract readonly class IntegerValue implements EqualableValueObject, PersistableValueObject, Stringable
{
    public function __construct(protected int $value) {}

    public static function fromInteger(int $value): static
    {
        return new static($value);
    }

    public function toInteger(): int
    {
        return $this->value;
    }

    #[\Override]
    public function __toString(): string
    {
        return (string) $this->value;
    }

    #[\Override]
    public function jsonSerialize(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function add(self $value): static
    {
        return new static($this->value + $value->toInteger());
    }

    public function sub(self $value): static
    {
        return new static($this->value - $value->toInteger());
    }

    public function multiply(self $value): static
    {
        return new static($this->value * $value->toInteger());
    }

    public function greater(self $value): bool
    {
        return $this->value > $value->toInteger();
    }

    public function less(self $value): bool
    {
        return $this->value < $value->toInteger();
    }

    public function negate(): static
    {
        return new static($this->value * -1);
    }

    public function isZero(): bool
    {
        return $this->value === 0;
    }

    public function isPositive(): bool
    {
        return $this->value > 0;
    }

    public function isNegative(): bool
    {
        return $this->value < 0;
    }

    #[\Override]
    public function rawValue(): int
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
        return ParameterType::INTEGER;
    }
}
