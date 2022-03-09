<?php declare(strict_types=1);

namespace App\Security\Api\Progress;

use App\Model\Progress\Entity\Project\Permission;
use App\Model\Progress\Entity\Project\Project;
use App\Model\Progress\Entity\User;
use App\Model\Progress\Repository\UserRepository;
use App\Security\UserIdentity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Uid\Ulid;

class ProjectVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const MANAGE_TASKS = 'manage_tasks';

    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::MANAGE_TASKS])) {
            return false;
        }

        if (!$subject instanceof Project) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var UserIdentity $userIdentity */
        $userIdentity = $token->getUser();
        $user = $this->userRepository->get(Ulid::fromString($userIdentity->getUserIdentifier()));

        /** @var Project $project */
        $project = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($project, $user);
            case self::EDIT:
                return $this->canEdit($project, $user);
            case self::MANAGE_TASKS:
                return $this->canAddTasks($project, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Project $project, User $user): bool
    {
        if ($project->getCreator() === $user) {
            return true;
        }

        if (in_array($user, $project->getMembers())) {
            return true;
        }

        return false;
    }

    private function canEdit(Project $project, User $user): bool
    {
        if ($project->getCreator() === $user) {
            return true;
        }

        foreach ($project->getMemberships() as $membership) {
            if ($membership->getMember() === $user) {
                return in_array(Permission::Edit, $membership->getPermissions());
            }
        }

        return false;
    }

    private function canAddTasks(Project $project, User $user): bool
    {
        if ($project->getCreator() === $user) {
            return true;
        }

        foreach ($project->getMemberships() as $membership) {
            if ($membership->getMember() === $user) {
                return in_array(Permission::ManageTasks, $membership->getPermissions());
            }
        }

        return false;
    }
}
