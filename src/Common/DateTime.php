<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Common;

use DateTimeZone;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\ParameterType;
use Netlogix\ValueObject\Service\NowFactory;
use Stringable;

abstract readonly class DateTime implements PersistableValueObject, Stringable
{
    final public const string DEFAULT_DATE_TIME_FORMAT = 'Y-m-d\TH:i:s\Z';

    private const string EMPTY = '';

    protected string $date;

    public function __construct(?string $_date)
    {
        if ($_date === null) {
            $_date = (new \DateTime())->format(DateTime::DEFAULT_DATE_TIME_FORMAT);
        }

        $this->date = $_date;
    }

    public static function fromString(string $date): static
    {
        return new static(trim($date));
    }

    public static function fromTimestamp(int $timestamp): static
    {
        $date = (new \DateTime('now', new DateTimeZone('UTC')))
            ->setTimestamp($timestamp)
            ->format(DateTime::DEFAULT_DATE_TIME_FORMAT);
        return new static($date);
    }

    public static function fromDateTime(?DateTimeInterface $date): static
    {
        if ($date instanceof DateTimeInterface) {
            return self::fromString(
                $date->setTimezone(new DateTimeZone('UTC'))->format(DateTime::DEFAULT_DATE_TIME_FORMAT),
            );
        }

        return self::createEmpty();
    }

    public static function createEmpty(): static
    {
        return new static(self::EMPTY);
    }

    public static function createNow(): static
    {
        return self::fromDateTime(NowFactory::createNow());
    }

    public function toString(): string
    {
        return $this->date;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->date;
    }

    public function toDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->date);
    }

    public function format(string $format): string
    {
        return $this->toDateTime()->format($format);
    }

    #[\Override]
    public function jsonSerialize(): string
    {
        return $this->date;
    }

    public function isEmpty(): bool
    {
        return $this->date === self::EMPTY;
    }

    public function isValid(): bool
    {
        return $this->date !== self::EMPTY;
    }

    public function equals(?self $other): bool
    {
        return $this->toString() === ($other instanceof DateTime ? $other->toString() : self::EMPTY);
    }

    #[\Override]
    public function rawValue(): string
    {
        return $this->date;
    }

    #[\Override]
    public static function fromRawValue(mixed $value): static
    {
        return static::fromDateTime($value);
    }

    #[\Override]
    public function rawType(): ParameterType
    {
        return ParameterType::STRING;
    }
}
