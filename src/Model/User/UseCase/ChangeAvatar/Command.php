<?php declare(strict_types=1);

namespace App\Model\User\UseCase\ChangeAvatar;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid   $userId,
        public string $imagePath,
        public int    $cropXPosition,
        public int    $cropYPosition,
        public int    $cropWidth,
        public int    $cropHeight,
    ) {
    }
}
