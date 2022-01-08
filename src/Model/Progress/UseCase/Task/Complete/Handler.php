<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Task\Complete;

use App\Model\Progress\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    public function __construct(
        private TaskRepository         $taskRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $task = $this->taskRepository->get($command->taskId);

        $task->setCompletedAt($command->completedAt);

        $this->entityManager->flush();
    }
}
