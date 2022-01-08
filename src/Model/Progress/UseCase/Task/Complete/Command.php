<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Task\Complete;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid $taskId,
        public \DateTimeImmutable $completedAt,
    ) {}
}
