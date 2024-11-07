<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Service;

use Netlogix\ValueObject\Common\ValueObject;
use Symfony\Component\Uid\Uuid;

class ValueObjectBasedUuidFactory
{
    public static function createUuidFromValueObject(
        string $suffix,
        ValueObject ...$valueObjects
    ): Uuid {
        return Uuid::fromBinary(
            hex2bin(
                md5(
                    json_encode(
                        array_merge(
                            $valueObjects,
                            [$suffix]
                        )
                    )
                )
            )
        );
    }
}
