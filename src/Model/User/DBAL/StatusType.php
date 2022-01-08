<?php declare(strict_types=1);

namespace App\Model\User\DBAL;

use App\Model\User\Entity\Status;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class StatusType extends Type
{
    public const USER_STATUS = 'user_status';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::USER_STATUS;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof Status) {
            throw new \InvalidArgumentException('Invalid user status');
        }

        return $value->value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Status
    {
        return Status::from($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }

    public function getName(): string
    {
        return self::USER_STATUS;
    }
}
