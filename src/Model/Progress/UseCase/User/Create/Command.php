<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\User\Create;

use Symfony\Component\Uid\Ulid;

class Command
{
    public Ulid $id;
    public string $name;
}
