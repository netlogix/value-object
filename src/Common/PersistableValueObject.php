<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Common;

use Doctrine\DBAL\ParameterType;
use Netlogix\ValueObject\DependencyInjection\PersistableValueObjectPass;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

#[Exclude]
#[Autoconfigure(tags: [PersistableValueObjectPass::VALUE_OBJECT_TAG], shared: false)]
interface PersistableValueObject extends ValueObject
{
    public static function fromRawValue($value): static;

    public function rawValue(): mixed;

    public function rawType(): ParameterType;
}
