<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Task\Create;

use App\Model\Progress\Entity\Task;
use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\TaskRepository;
use App\Model\Progress\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class Handler
{
    public function __construct(
        private TaskRepository         $taskRepository,
        private UserRepository         $userRepository,
        private ProjectRepository      $projectRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): Ulid
    {
        $user = $this->userRepository->get($command->userId);
        $project = null;

        if ($command->projectId) {
            $project = $this->projectRepository->get($command->projectId);
        }

        $task = (new Task(
            new Ulid(),
            $command->title,
            new \DateTimeImmutable(),
            $user,
        ))
            ->setDescription($command->description)
            ->setProject($project);

        $this->taskRepository->add($task);
        $this->entityManager->flush();

        return $task->getId();
    }
}
