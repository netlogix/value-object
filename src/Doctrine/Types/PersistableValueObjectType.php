<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use InvalidArgumentException;
use LogicException;
use Netlogix\ValueObject\Common\Identifier;
use Netlogix\ValueObject\Common\BooleanValue;
use Netlogix\ValueObject\Common\DateTime;
use Netlogix\ValueObject\Common\IntegerValue;
use Netlogix\ValueObject\Common\PersistableValueObject;
use Netlogix\ValueObject\Common\StringValue;

final class PersistableValueObjectType extends Type
{
    private const array BUILT_IN_TYPE_MAP = [
        BooleanValue::class => Types::BOOLEAN,
        DateTime::class => Types::DATETIMETZ_IMMUTABLE,
        Identifier::class => Types::STRING,
        IntegerValue::class => Types::INTEGER,
        StringValue::class => Types::STRING,
    ];
    private string $typeName;
    private string $valueObjectFQCN;
    private string $builtInTypeName;

    public static function create(string $typeName, string $valueObjectFQCN): self
    {
        if (!is_subclass_of($valueObjectFQCN, PersistableValueObject::class)) {
            throw new LogicException(
                sprintf(
                    "Value object %s must implement %s interface",
                    $valueObjectFQCN,
                    PersistableValueObject::class
                )
            );
        }

        $valueObjectType = new self();
        $valueObjectType->typeName = $typeName;
        $valueObjectType->valueObjectFQCN = $valueObjectFQCN;

        foreach (self::BUILT_IN_TYPE_MAP as $abstractValueObjectFQCN => $builtInTypeName) {
            if (is_subclass_of($valueObjectFQCN, $abstractValueObjectFQCN)) {
                $valueObjectType->builtInTypeName = $builtInTypeName;
                return $valueObjectType;
            }
        }

        throw new LogicException(
            sprintf(
                'No built-in Doctrine DBAL type found for value object %s',
                $valueObjectFQCN,
            )
        );
    }

    public function getName(): string
    {
        return $this->typeName;
    }

    private function getBuiltInType(): Type
    {
        return $this->getType($this->builtInTypeName);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->getBuiltInType()->getSQLDeclaration($column, $platform);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof $this->valueObjectFQCN) {
            throw new InvalidArgumentException(
                sprintf(
                    'Value object of type %s expected, got %s',
                    $this->valueObjectFQCN,
                    get_debug_type($value)
                )
            );
        }

        return $this->getBuiltInType()->convertToDatabaseValue($value->rawValue(), $platform);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        $value = $this->getBuiltInType()->convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        $valueObject = call_user_func([$this->valueObjectFQCN, 'fromRawValue'], $value);

        if (!$valueObject instanceof $this->valueObjectFQCN) {
            throw new LogicException(
                sprintf(
                    "%s::fromRawValue must return an instance of %s, returned %s",
                    $this->valueObjectFQCN,
                    $this->valueObjectFQCN,
                    get_debug_type($valueObject)
                )
            );
        }

        return $valueObject;
    }
}
