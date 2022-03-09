<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\Update;

use App\Model\Progress\ValueObject\Color;
use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid    $id,
        public string  $title,
        public ?string $description,
        public Color   $color,
    ) {}
}
