<?php declare(strict_types=1);

namespace App\Model\User\UseCase\Register\Confirm;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid   $id,
        public string $confirmToken,
    ) {
    }
}
