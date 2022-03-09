<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\CreateInviteByEmail;

use App\Model\Progress\Entity\Project\Permission;
use Symfony\Component\Uid\Ulid;

class Command
{
    public function __construct(
        public Ulid   $projectId,
        public string $email,

        /**
         * @var Permission[]
         */
        public array $permissions,
    ) {
    }
}
