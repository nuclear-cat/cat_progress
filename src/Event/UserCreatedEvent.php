<?php declare(strict_types=1);

namespace App\Event;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\Ulid;

class UserCreatedEvent
{
    private string $userId;
    private string $name;

    public function __construct(Ulid $userId, string $name)
    {
        $this->userId = $userId->toRfc4122();
        $this->name = $name;
    }

    public function getUserId(): Ulid
    {
        return Ulid::fromString($this->userId);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
