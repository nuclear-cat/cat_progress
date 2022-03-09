<?php declare(strict_types=1);

namespace App\Model\Progress\DBAL;

use App\Model\Progress\Entity\Project\Permission;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ProjectPermission extends Type
{
    public const PERMISSION = 'progress_project_permission';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::PERMISSION;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!$value instanceof Permission) {
            throw new \InvalidArgumentException('Invalid weekday.');
        }

        return $value->value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Permission
    {
        return Permission::from($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }

    public function getName(): string
    {
        return self::PERMISSION;
    }
}