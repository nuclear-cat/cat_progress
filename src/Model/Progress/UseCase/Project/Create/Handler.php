<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\Create;

use App\Model\Progress\Entity\Project\Project;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class Handler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private ProjectRepository $projectRepository,
    ) {
    }

    public function handle(Command $command): Ulid
    {
        $user = $this->userRepository->get($command->userId);

        $project = (new Project(
            new Ulid(),
            $command->title,
            $user,
        ))
            ->setDescription($command->description)
            ->setColor($command->color);

        $this->projectRepository->add($project);
        $this->entityManager->flush();

        return $project->getId();
    }
}
