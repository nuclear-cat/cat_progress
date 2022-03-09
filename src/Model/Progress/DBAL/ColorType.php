<?php declare(strict_types=1);

namespace App\Model\Progress\DBAL;

use App\Model\Progress\ValueObject\Color;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use JetBrains\PhpStorm\Pure;

final class ColorType extends StringType
{
    public const NAME = 'progress_color';

    #[Pure] public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Color ? $value->value : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Color
    {
        return !empty($value) ? Color::from($value) : null;
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
