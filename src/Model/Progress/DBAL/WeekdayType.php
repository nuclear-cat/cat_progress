<?php declare(strict_types=1);

namespace App\Model\Progress\DBAL;

use App\Model\Progress\Entity\Weekday;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class WeekdayType extends Type
{
    public const WEEKDAY = 'progress_weekday';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::WEEKDAY;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof Weekday) {
            throw new \InvalidArgumentException('Invalid weekday.');
        }

        return $value->value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Weekday
    {
        return Weekday::from($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }

    public function getName(): string
    {

        return self::WEEKDAY;
    }
}
