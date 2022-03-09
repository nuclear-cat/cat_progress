<?php declare(strict_types=1);

namespace App\Model\Progress\DBAL;

use App\Model\Progress\Entity\HabitCompletionType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class HabitCompletionTypeType extends StringType
{
    public const TYPE = 'progress_habit_completion_type';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::TYPE;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!$value instanceof HabitCompletionType) {
            throw new \InvalidArgumentException('Invalid habit completion type.');
        }

        return $value->value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?HabitCompletionType
    {
        if ($value === null) {
            return null;
        }

        return HabitCompletionType::from($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}
