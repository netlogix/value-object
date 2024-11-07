<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\Service;

use DateTimeZone;
use DateTimeImmutable;

final class NowFactory
{
    public static function createNow(): DateTimeImmutable
    {
        /**
         * @todo: Find a Now equivalent in Symfony
         */
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }
}
