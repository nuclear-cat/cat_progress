<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\Create;

use App\Model\Progress\ValueObject\Color;
use Symfony\Component\Uid\Ulid;

class Command
{
    public string $title;
    public ?string $description;
    public Ulid $userId;
    public Color $color;
}
