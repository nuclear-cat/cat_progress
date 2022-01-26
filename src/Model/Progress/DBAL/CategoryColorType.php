<?php declare(strict_types=1);

namespace App\Model\Progress\DBAL;

use App\Model\Progress\Entity\CategoryColor;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use JetBrains\PhpStorm\Pure;

final class CategoryColorType extends StringType
{
    public const NAME = 'progress_category_color';

    #[Pure] public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof CategoryColor ? $value->value : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CategoryColor
    {
        return !empty($value) ? CategoryColor::from($value) : null;
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
