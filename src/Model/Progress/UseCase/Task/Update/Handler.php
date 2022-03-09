<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Task\Update;

use App\Model\Progress\Repository\Project\ProjectRepository;
use App\Model\Progress\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private TaskRepository         $taskRepository,
        private EntityManagerInterface $entityManager,
        private ProjectRepository      $projectRepository,
    ) {
    }

    public function handle(Command $command): void
    {
        $task = $this->taskRepository->get($command->taskId);
        $project = null;

        if ($command->projectId) {
            $project = $this->projectRepository->getByIdAndCreator($command->projectId, $task->getUser());
        }

        $task
            ->setTitle($command->title)
            ->setDescription($command->description)
            ->setProject($project);

        $this->entityManager->flush();
    }
}
