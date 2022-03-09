<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\CreateInviteByEmail;

use App\Model\Progress\Entity\Project\Invite;
use App\Model\Progress\Entity\Project\InvitePermission;
use App\Model\Progress\Repository\Project\InvitePermissionRepository;
use App\Model\Progress\Repository\Project\InviteRepository;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Service\InviteSender;
use App\Model\Progress\Service\InviteTokenGenerator;
use App\Model\Progress\ValueObject\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class Handler
{
    public function __construct(
        private ProjectRepository          $projectRepository,
        private InviteRepository           $inviteRepository,
        private InvitePermissionRepository $invitePermissionRepository,
        private EntityManagerInterface     $entityManager,
        private InviteTokenGenerator       $inviteTokenGenerator,
        private InviteSender               $inviteSender,
    ) {
    }

    public function handle(Command $command): void
    {
        $project = $this->projectRepository->get($command->projectId);
        $token = $this->inviteTokenGenerator->generate();

        $invite = new Invite(
            new Ulid(),
            $project,
            $token,
            new \DateTimeImmutable(),
        );
        $this->inviteRepository->add($invite);

        foreach ($command->permissions as $permission) {
            $invitePermission = new InvitePermission(
                new Ulid(),
                $invite,
                $permission
            );
            $this->invitePermissionRepository->add($invitePermission);
            $invite->addInvitePermission($invitePermission);
        }

        $this->inviteSender->send(new Email($command->email), $invite);

        $this->entityManager->flush();
    }
}
