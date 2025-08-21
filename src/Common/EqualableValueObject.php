<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Common;

use Doctrine\DBAL\ParameterType;
use Netlogix\ValueObject\DependencyInjection\PersistableValueObjectPass;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * An EqualableValueObject is a ValueObject that can be compared for equality.
 *
 * @method bool equals(static $other)
 */
interface EqualableValueObject extends ValueObject {}
