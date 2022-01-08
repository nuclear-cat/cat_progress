<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Create;

use Symfony\Component\Uid\Ulid;

class Command
{
    public string $title;
    public int $totalPoints;
    public ?string $description;
    /** @var array  */
    public array $weekdays;
    public Ulid $userId;
    public Ulid $categoryId;
}
