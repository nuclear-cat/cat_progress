<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Incomplete;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid $habitId,
        public Ulid $completionId,
    ) {}
}
