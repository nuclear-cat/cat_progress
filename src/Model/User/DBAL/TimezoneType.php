<?php

namespace App\Model\User\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use JetBrains\PhpStorm\Pure;

final class TimezoneType extends StringType
{
    public const NAME = 'auth_user_timezone';

    #[Pure] public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof \DateTimeZone ? $value->getName() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?\DateTimeZone
    {
        return !empty($value) ? new \DateTimeZone($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}