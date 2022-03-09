<?php declare(strict_types=1);

namespace App\Model\User\UseCase\Update;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public string $name,
        public Ulid $userId,
    ) {
    }
}
