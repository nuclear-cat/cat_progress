<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Update;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid $id,
        public Ulid $categoryId,
        public string $title,
        public int $totalPoints,
        public ?string $description,
        public array $weekdays = [],
    ) {}
}
