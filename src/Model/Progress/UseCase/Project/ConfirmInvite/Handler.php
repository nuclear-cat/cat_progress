<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\ConfirmInvite;

use App\Model\Progress\Entity\Project\Membership;
use App\Model\Progress\Entity\Project\MembershipPermission;
use App\Model\Progress\Repository\Project\InviteRepository;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class Handler
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private InviteRepository  $inviteRepository,
        private UserRepository    $userRepository,
        private EntityManagerInterface    $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $project = $this->projectRepository->get($command->projectId);
        $invite = $this->inviteRepository->get($command->inviteId);
        $user = $this->userRepository->get($command->userId);

        $membership = new Membership(
            new Ulid(),
            $user,
            $project,
            new \DateTimeImmutable(),
        );
        $this->entityManager->persist($membership);

        foreach ($invite->getInvitePermissions() as $invitePermission) {
            $membershipPermission = new MembershipPermission(
                new Ulid(),
                $membership,
                $invitePermission->getPermission(),
            );

            $this->entityManager->persist($membershipPermission);
        }

        $this->entityManager->flush();
    }
}
