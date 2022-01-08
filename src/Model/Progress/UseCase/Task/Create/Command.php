<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Task\Create;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public string $title,
        public ?string $description,
        public Ulid $userId,
    ) {}
}
