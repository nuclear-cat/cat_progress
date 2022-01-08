<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Delete;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid $id,
    ) {}
}
