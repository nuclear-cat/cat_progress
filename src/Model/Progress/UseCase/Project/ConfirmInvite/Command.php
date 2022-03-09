<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\ConfirmInvite;

use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid $inviteId,
        public Ulid $projectId,
        public Ulid $userId,
    ) {
    }
}
