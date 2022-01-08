<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Category\Create;

use App\Model\Progress\Entity\CategoryColor;
use Symfony\Component\Uid\Ulid;

class Command
{
    public string $title;
    public ?string $description;
    public Ulid $userId;
    public CategoryColor $color;
}
