<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Complete;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        readonly public Ulid $habitId,
        readonly public \DateTimeImmutable $completedAt,
    ) {}
}
