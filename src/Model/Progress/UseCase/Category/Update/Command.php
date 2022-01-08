<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Category\Update;

use App\Model\Progress\Entity\CategoryColor;
use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid $id,
        public string $title,
        public ?string $description,
        public CategoryColor $color,
    ) {}
}
