<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Task\Delete;

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

        $this->taskRepository->remove($task);
        $this->entityManager->flush();
    }
}
