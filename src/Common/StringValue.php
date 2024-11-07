<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Common;

use Doctrine\DBAL\ParameterType;
use Stringable;

abstract readonly class StringValue implements PersistableValueObject, Stringable
{
    public function __construct(
        protected string $value
    ) {
    }

    public static function fromString(string $name): static
    {
        return new static($name);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return $this->value === '' || $this->value === '0';
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function rawValue(): string
    {
        return $this->value;
    }

    public static function fromRawValue(mixed $value): static
    {
        return static::fromString($value);
    }

    public function rawType(): ParameterType
    {
        return ParameterType::STRING;
    }
}
